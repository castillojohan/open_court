<?php

namespace App\Controller\Back;

use App\Repository\SlotRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PlanningController extends AbstractController
{
    #[Route('/account/manage/slots', name: 'app_account_manage_slots', methods:['GET'])]
    public function manageSlots(SlotRepository $slotRepository): Response
    {
        $slots = $slotRepository->findSlotsFromToday(); // more efficient
        //$slots = $slotRepository->findAll();
        return $this->render('/Back/slots/slots.html.twig', ['slots' => $slots]);
    }

    #[Route('/account/manage/delete/slot/{slotId}', name: 'app_account_manage_delete_slot', methods:['GET'])]
    public function deleteSlot($slotId, SlotRepository $slotRepository): Response
    {
        $slots = $slotRepository->find($slotId); // more efficient
        //$slots = $slotRepository->findAll();
        //! delete a time slot logic.
        return $this->render('/Back/slots/slots.html.twig', ['slots' => $slots]);
    }

}