<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MemberController extends AbstractController
{
    #[Route('/member-choice/{memberId}', name: 'app_member_choice', methods:['GET', 'POST'])]
    public function memberChoice(Member $memberId, Request $request): Response
    {
        if($memberId == null){
            return new Exception("Membre non trouvé ( 404 )", Response::HTTP_NOT_FOUND);
        }

        if(!$memberId->getPinCode()){
            $session = $request->getSession();
            $session->set('member', $memberId);
            return $this->redirectToRoute('app_account');
        }

        if($request->isMethod('POST')){
            if($memberId->getPinCode() == $request->request->get('pincode')){
                $session = $request->getSession();
                $session->set('member', $memberId);
                return $this->redirectToRoute('app_account');
            }
            $this->addFlash('error', 'Mauvais code pin');
            return $this->redirectToRoute('app_account');
        }
        
        return $this->render('/security/pincode.html.twig', [
            'member'=> $memberId,
            'last_username' => "",
        ]);
    }

    #[Route('/register-member', name: 'app_register_member', methods:['GET', 'POST'])]
    public function memberRegistration(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $member = new Member();
        $member->setUser($this->getUser());
        $form = $this->createForm(MemberType::class, $member);
        
        if($request->isMethod('POST')){
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($member);
                $entityManager->flush();
                return $this->redirectToRoute('app_account');
            }
        }
        return $this->render('Front/register-member.html.twig', ["form" => $form, "errors"=>""]);
    }

    #[Route('/member/modify/{member}', name: 'app_member_modify', methods: ['GET', 'POST'])]
    public function memberModify(Member $member, MemberRepository $memberRepository, Request $request): Response
    {
        // 404 case
        if(!$member){
            return new Exception('Not Found', Response::HTTP_NOT_FOUND);
        }
        
        // GET request case
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $formPinCode = $form->get('pincode')->getData();
            if($formPinCode !== null){
               $member->setPinCode($formPinCode); 
            }
            $memberRepository->add($member, true);
            $this->addFlash('Success', 'Membre bien modifié');
            return $this->redirectToRoute('app_account');
        }

        return $this->render('Front/modify-member.html.twig', ['member' => $member, 'form' => $form]);
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