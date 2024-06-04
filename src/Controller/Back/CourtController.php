<?php

namespace App\Controller\Back;

use App\Entity\Court;
use App\Form\CourtType;
use App\Repository\CourtRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CourtController extends AbstractController
{
    #[Route("/account/manage/courts", name:"app_account_manage_courts", methods:['GET'])]
    public function manageCourt(CourtRepository $courtRepository): Response
    {
        $courts = $courtRepository->findAll();
        return $this->render('/Back/court/courts.html.twig', [ 'courts' => $courts ]);
    }

    #[Route("/account/manage/court/add", name:"app_account_manage_court_add", methods:['GET', 'POST'])]
    public function addCourt(Request $request, EntityManagerInterface $entityManager): Response
    {
        $court = new Court();
        $form = $this->createForm(CourtType::class, $court);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $entityManager->persist($court);
            $entityManager->flush();
            $this->addFlash('success', 'Le nouveau court à été ajouté.');
            return $this->redirectToRoute('app_account_manage_courts');
        }

        return $this->render('/Back/court/register-court.html.twig', ["form" => $form ]);
    }
    
    #[Route("/account/manage/modify/court/{courtId}", name:"app_account_manage_modify_court", methods:['GET', 'POST'])]
    public function modifyCourt($courtId, CourtRepository $courtRepository): Response
    {
        $courts = $courtRepository->findAll();
        return $this->render('/Back/court/courts.html.twig', [ 'courts' => $courts ]);
    }

    #[Route("/account/manage/delete/court/{courtId}", name:"app_account_manage_delete_court", methods:['GET', 'POST'])]
    public function deleteCourt($courtId, CourtRepository $courtRepository, EntityManagerInterface $entityManager): Response
    {
        if($courtId == null || $courtId ==""){
            return $this->createNotFoundException("La ressource n'a pas été trouvée");
        }
        $court = $courtRepository->find($courtId);
        $entityManager->remove($court);
        $entityManager->flush();
        $this->addFlash('success', "Court {$court->getName()}, bien supprimé.");
        return $this->redirectToRoute('app_account_manage_courts');
    }

}