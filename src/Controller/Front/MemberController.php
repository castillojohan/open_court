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

class MemberController extends AbstractController
{
    #[Route('/account/member-choice/{member}', name: 'app_member_choice', methods:['GET', 'POST'])]
    public function memberChoice(Member $member, Request $request)
    {
        $this->controlException($member, $this->getUser());

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
        return $this->render('Front/register-member.html.twig', ["form" => $form, "errors"=>""]);
    }

    #[Route('/account/member/modify/{member}', name: 'app_member_modify', methods: ['GET', 'POST'])]
    public function memberModify(Member $member, MemberRepository $memberRepository, Request $request): Response
    {
        $session = $request->getSession();
        $currentMember = $session->get("member");

        $this->controlException($member, $this->getUser(), $currentMember);
        
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

    #[Route('/account/member/delete/{member}', name: 'app_member_delete')]
    public function memberDeletion(Member $member,SlotRepository $slotRepository, EntityManagerInterface $entityManager): Response
    {
        $this->controlException($member, $this->getUser());
        $slots = $slotRepository->findBy(['memb' => $member]);

        foreach ($slots as $slot) {
            $entityManager->remove($slot);
        }
        $entityManager->remove($member);
        $entityManager->flush();

        return $this->redirectToRoute('app_account');
    }

    /**
     * function to control 404/403 exception
     * @param Member $member (memberID from route parameter)
     * @param User $user (current User)
     * @param Member|null $currentMember ($current member from session, if exist|if needed)
     * 
     * @return throw Exception (404/403) 
     */
    public function controlException($member, $user, $currentMember=null)
    {
        switch (true) {
            case $member->getId() == null:
                throw $this->createNotFoundException("Ressource non trouvée");
                break;
            case $member->getUser() !== $user:
                throw $this->createAccessDeniedException("Cette ressource ne vous appartiens pas");
                break;
            // in this case, check if member to modify is the same as the current member and if the member to modify isn't minor ( case which minor can't delete or modify his own profil ) 
            case ($currentMember && ($member->getId() !== $currentMember->getId() && $member->getAge() > 17)):
                throw $this->createAccessDeniedException("Vous ne pouvez pas accéder a cette ressource");
                break;
        }
    }
}