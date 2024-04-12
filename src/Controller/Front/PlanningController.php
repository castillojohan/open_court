<?php

namespace App\Controller\Front;

use App\Entity\Slot;
use App\Repository\CourtRepository;
use App\Repository\MemberRepository;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use App\Service\TimeSlotRules;
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
    #[Route('/account/planning', name:'app_planning', methods:['GET','POST'])]
    public function getPlanning(CourtRepository $courtRepository, SlotRepository $slotRepository, Request $request): Response
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
    public function bookSlot(TimeSlotRules $timeSlotRules, CourtRepository $courtRepository, MemberRepository $memberRepository, Request $request, ValidatorInterface $validator, EntityManagerInterface $entityManager, HubInterface $hub): JsonResponse
    {
        $member = $request->getSession()->get('member');
        $user = $member->getUser();
        
        $slot = new Slot();

        $json = json_decode($request->getContent(), true);
        $startTime = new DateTimeImmutable($json['start_slot']);
        $endTime = new DateTimeImmutable($json['end_slot']);
        $court = $courtRepository->find($json['court_id']);

        $slot->setMemb($memberRepository->find($json['member_id']))
            ->setCourt($court)
            ->setStartAt($startTime)
            ->setEndAt($endTime);

   
        $hasErrors = $timeSlotRules->canBookTimeSlot($member, $user, $slot);
        if($hasErrors[0]){
            return $this->json(
                ['error' => $hasErrors[1]['error']],
                Response::HTTP_ACCEPTED,
                [],
                []
            );
        }

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
            [
                "success" => ["Créneau horaire, correctement reservé"],
                "slot" => $slot
            ],
            200,
            [],
            ['groups'=>'get_slots']
        );
    }

    #[Route('/account/booking-history', name:'app_booking-history', methods:'GET')]
    public function bookingHistory(MemberRepository $memberRepository, SlotRepository $slotRepository): Response
    {
        //! Tenter de récup les depuis le slot Repo ?
        $user = $this->getUser();
        
        $membersList = $memberRepository->findBy(['user'=>$user]);
        $slots = $slotRepository->findBy(['memb'=>$membersList], ['startAt'=>"ASC"]);
        /*
        foreach ($membersList as $member) {
            $slots[$member->getFirstName()] = $member->getSlots();
        };*/
        return $this->render('Front/booking-history.html.twig', ['members'=> $membersList, "slots"=>$slots]);
    }

    #[Route('/account/test', name:'app_planning_test', methods:['GET'])]
    public function testService(TimeSlotRules $slotService,CourtRepository $courtRepository, Request $request): Response
    {   
        $member = $request->getSession()->get('member');
        $slot = new Slot();
        $slot->setMemb($member)
            ->setStartAt((new \DateTimeImmutable('2024-04-10 20:00:00')))
            ->setEndAt((new \DateTimeImmutable('2024-04-10 21:00:00')))
            ->setCourt($courtRepository->find(1));

        $user = $member->getUser();
        $test = $slotService->canBookTimeSlot($member, $user, $slot);
        //dd($test[1]);
        if(count($test[1]) > 0 ){
            return $this->json([
                "error" => $test[1]['error']
            ],
            200,
            [],
            []
            );
        }

        return $this->render('/Front/planning.html.twig', [
            'member' => $member,
            'courts' => "Terrain Factice"      
        ]);
    }
}