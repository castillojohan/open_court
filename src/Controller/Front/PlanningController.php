<?php

namespace App\Controller\Front;

use App\Repository\CourtRepository;
use App\Repository\MemberRepository;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/planning', name:'app_planning', methods:['GET','POST'])]
    public function getPlanning(CourtRepository $courtRepository, Request $request)
    {   
        return $this->render('/Front/planning.html.twig', [
            'courts' => $courtRepository->findAll()]
        );
    }

    /**
     * JSON requirements for js module
     * 
     * @return JsonResponse ( contains reserved slots from today date, to avoid overload of requests in the futur )
     */
    #[Route('/booked-slots', name:'app_api_booked-slot')]
    public function bookedSlots(SlotRepository $slotRepository, Request $request)
    {   
        $member = $request->getSession()->get('member');
        return $this->json(
            [
                'slots'=> $slotRepository->findSlotsFromToday(),
                'member' => $member,
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
    #[Route('/book-slot', name:'app_api_book-slot')]
    public function bookSlot(SlotRepository $slotRepository, UserRepository $userRepository, MemberRepository $memberRepository)
    {
        /*
        $currentMember = $this->getUser();
        $member = $memberRepository->find($currentMember);
        */
        return $this->json(
            ['slots'=> $slotRepository->findSlotsFromToday()],
            Response::HTTP_OK,
            [],
            ['groups' =>'get_slots']
        );
    }
}