<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function userRegistration(Request $request): Response
    {
        if($request->getMethod() === 'GET'){
            return $this->render('Front/register.html.twig', ['last_username' => '']);
        }
        else{
            //* TODO Call to symfony form, to register one USER and can create many member ? ( FamilyUseCase ?)
            //* one user can pay for his group family et get attached to his account
            return new Response('Hello');
        }
    }

    #[Route('/account', name: 'app_account', methods: 'GET')]
    public function userAccount(): Response
    {
        $currentUser = $this->getUser()->getEmail();
        $this->addFlash('success', "Bienvenue, $currentUser");

        return $this->render('Front/account.html.twig', []);
    }
}