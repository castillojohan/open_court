<?php

namespace App\Controller\Back;

use App\Repository\CourtRepository;
use App\Repository\MemberRepository;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use App\Service\StatService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StatsController extends AbstractController{
    #[Route('/account/admin/data', name:'app_account_admin_data', methods:'GET')]
    public function getDatas(MemberRepository $memberRepository, UserRepository $userRepository, SlotRepository $slotRepository, CourtRepository $courtRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $members = $memberRepository->findAll();
        //$slots = $slotRepository->findAll();
        
        $courts = $courtRepository->findAll();
        $courtOccupation = StatService::sortSlotByCourt($courts);
        return new JsonResponse(['datas' => 
        [
            'users' => $users,
            'members' => $members,
            'courts' => $courtOccupation,
        ]],
        200,
        []);
    }
}