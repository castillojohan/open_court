<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Entity\User;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
    public function userAccount(UserRepository $userRepository, MemberRepository $memberRepository, Request $request): Response
    {
        $userId =  $this->getUser()->getId();
        $currentUser = $userRepository->find($userId);
        $userMail = $currentUser->getEmail();
        $memberCollection = $memberRepository->findBy(['user'=>$userId]);
        
        $this->addFlash('success', "Bienvenue, $userMail");

        return $this->render('Front/account.html.twig', ['members'=> $memberCollection, 'session'=>$request->getSession()]);
    }

    #[Route('/member-choice/{memberId}', name: 'app_member_choice', methods:'GET')]
    public function memberChoice(Member $memberId, Request $request): Response
    {
        $session = $request->getSession();
        $session->set('member', $memberId);
        return $this->redirectToRoute('app_account');
    }


    #[Route('/register-member', name: 'app_register_member', methods:['GET', 'POST'])]
    public function memberRegisteration(Request $request, EntityManagerInterface $entityManager): Response
    {
        $member = new Member();
        $member->setUser($this->getUser());
        $form = $this->createForm(MemberType::class, $member);
        
        if($request->isMethod('POST')){

            $submittedToken = $request->getPayload()->get('_token');
            if($this->isCsrfTokenValid('member', $submittedToken)){
                $form->submit($request->getPayload()->all());

                if($form->isSubmitted() && $form->isValid()){
                    $entityManager->persist($member);
                    $entityManager->flush();
                }
                return $this->redirectToRoute('app_account');
            }
            $this->addFlash('error', 'Oups something went wrong');
            return $this->render('Front/register-member.html.twig');
        }
        return $this->render('Front/register-member.html.twig');
    }

    #[Route('/member/delete/{memberId}', name: 'app_member_delete')]
    public function memberDeletion(Member $memberId, EntityManagerInterface $entityManager): Response
    {
        if(!$memberId){
            return new Exception('Not Found', Response::HTTP_NOT_FOUND);
        }
        
        $entityManager->remove($memberId);
        $entityManager->flush();
        return $this->redirectToRoute('app_account');
    }

}