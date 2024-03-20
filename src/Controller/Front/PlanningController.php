<?php

namespace App\Controller\Front;

use App\Entity\Slot;
use App\Repository\CourtRepository;
use App\Repository\MemberRepository;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlanningController extends AbstractController
{
    #[Route('/planning', name:'app_planning', methods:['GET','POST'])]
    public function getPlanning(CourtRepository $courtRepository, Request $request): Response
    {   
        $member = $request->getSession()->get('member');
        return $this->render('/Front/planning.html.twig', [
            'courts' => $courtRepository->findAll(),
            'member' => $member        
        ]);
    }

    /**
     * JSON requirements for js module
     * 
     * @return JsonResponse ( contains reserved slots from today date, to avoid overload of requests in the futur )
     */
    #[Route('/booked-slots', name:'app_api_booked-slot')]
    public function bookedSlots(SlotRepository $slotRepository,MemberRepository $memberRepository, Request $request): JsonResponse
    {   
        $member = $request->getSession()->get('member');
        $user = $member->getUser();
        $membersList = $memberRepository->findBy(['user'=> $user->getId()]);
        
        return $this->json(
            [
                'slots'=> $slotRepository->findSlotsFromToday(),
                'currentMember' => $member,
                'membersList' => $membersList
            ],
            Response::HTTP_OK,
            [],
            ['groups' =>['get_slots', 'get_member']]
        );
    }

    /**
     * JSON requirements for js module POST method
     * 
     * @return JsonResponse ( contains reserved slots by the member )
     */
    #[Route('/book-slot', name:'app_api_book-slot', methods:'POST')]
    public function bookSlot(CourtRepository $courtRepository, MemberRepository $memberRepository, Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, HubInterface $hub): JsonResponse
    {
        $slot = new Slot();

        $json = json_decode($request->getContent(), true);
        $startTime = new DateTimeImmutable($json['start_slot']);
        $endTime = new DateTimeImmutable($json['end_slot']);
        $court = $courtRepository->find($json['court_id']);

        $slot->setMemb($memberRepository->find($json['member_id']))
            ->setCourt($court)
            ->setStartAt($startTime)
            ->setEndAt($endTime);

        $errors = $validator->validate($slot);
        if(count($errors) > 0){
            return new JsonResponse(['errors'=>$errors], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        
        $entityManager->persist($slot);
        $entityManager->flush();

        $update = new Update(
            ['booked'],
            json_encode(['data' => 'Créneau reservé'])
        );
        $hub->publish($update);

        return $this->json(
            ["slot" => $slot],
            200,
            [],
            ['groups'=>'get_slots']
        );
    }
    #[Route('/booking-history', name:'app_booking-history', methods:'GET')]
    public function bookingHistory(SlotRepository $slotRepository, MemberRepository $memberRepository): Response
    {
        $user = $this->getUser();
        $membersList = $memberRepository->findBy(['user'=>$user]);
        $membersSlots = [];
        foreach($membersList as $member){
            $slotList = $slotRepository->findBy(['memb'=>$member]);
            dd($slotList);
            if(!empty($slotList)){
                $membersSlots[$member->getFirstName()] = $slotList;
            }
        }
        return $this->render('Front/booking-history.html.twig', ['slots'=>$membersSlots]);
    }

}