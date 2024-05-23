<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Form\MemberType;
use App\Repository\MemberRepository;
use App\Repository\SlotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Utils\Utils;

class MemberController extends AbstractController
{
    #[Route('/account/member-choice/{member}', name: 'app_member_choice', methods:['GET', 'POST'])]
    public function memberChoice(Member $member, Request $request)
    {
        Utils::controlException($member, $this->getUser());

        if(!$member->getPinCode()){
            $session = $request->getSession();
            $session->set('member', $member);
            return $this->redirectToRoute('app_account');
        }

        if($request->isMethod('POST')){
            if($member->getPinCode() == $request->request->get('pincode')){
                $session = $request->getSession();
                $session->set('member', $member);
                return $this->redirectToRoute('app_account');
            }
            $this->addFlash('error', 'Mauvais code pin');
            return $this->redirectToRoute('app_account');
        }
        
        return $this->render('/security/pincode.html.twig', [
            'member'=> $member,
            'last_username' => "",
        ]);
    }

    #[Route('/account/register-member', name: 'app_register_member', methods:['GET', 'POST'])]
    public function memberRegistration(Request $request, EntityManagerInterface $entityManager): Response
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
        return $this->render('Front/account/register-member.html.twig', ["form" => $form, "errors"=>""]);
    }

    #[Route('/account/member/modify/{member}', name: 'app_member_modify', methods: ['GET', 'POST'])]
    public function memberModify(Member $member, MemberRepository $memberRepository, Request $request): Response
    {
        $session = $request->getSession();
        $currentMember = $session->get("member");

        Utils::controlException($member, $this->getUser(), $currentMember);
        
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

        return $this->render('Front/account/modify-member.html.twig', ['member' => $member, 'form' => $form]);
    }

    #[Route('/account/member/delete/{member}', name: 'app_member_delete', methods:['GET', 'POST'])]
    public function memberDeletion(Member $member,SlotRepository $slotRepository, EntityManagerInterface $entityManager, Request $request): Response
    {

        Utils::controlException($member, $this->getUser());
        $slots = $slotRepository->findBy(['memb' => $member]);
        //!\ TODO finisg this controller's route, remove or modify Member/User entity ? 
        if($request->isMethod('POST')){
            foreach ($slots as $slot) {
                $entityManager->remove($slot);
            }
            $entityManager->remove($member);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_account');
        }

        return $this->render('/Front/account/delete-member-confirmation.html.twig', ["member" => $member]);
    }
}