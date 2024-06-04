<?php

namespace App\Controller\Back;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController{
    #[Route('/account/manage/users', name: 'app_account_manage_users', methods:['GET'])]
    public function manageUsers(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('/Back/users/users.html.twig', ['users' => $users]);
    }

    #[Route('/account/manage/user/suspend/{userId}', name: 'app_account_manage_suspend_user', methods:['GET', 'POST'])]
    public function suspendUser($userId, UserRepository $userRepository): Response
    {
        $member = $userRepository->find($userId);
        /**
         *  Logic to déactivate for a duration
         */
        return $this->redirectToRoute('app_account_manage_users');
    }

    #[Route('/account/manage/user/delete/{userId}', name: 'app_account_manage_delete_user', methods:['GET', 'POST'])]
    public function deleteUser($userId, UserRepository $userRepository): Response
    {
        $member = $userRepository->find($userId);
        /**
         *  Logic to delete an user
         */
        return $this->redirectToRoute('app_account_manage_users');
    }
}