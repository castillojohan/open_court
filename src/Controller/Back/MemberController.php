<?php 

namespace App\Controller\Back;

use App\Form\MemberType;
use App\Repository\MemberRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MemberController extends AbstractController{
    
    #[Route('/account/manage/members', name: 'app_account_manage_members', methods:['GET'])]
    public function manageMembers(MemberRepository $memberRepository): Response
    {
        $members = $memberRepository->findBy([], ['user' => 'ASC']);
        return $this->render('/Back/members/members.html.twig', ['members' => $members]);
    }

    #[Route('/account/manage/members/modify/{memberId}', name: 'app_account_manage_modify_member', methods:['GET', 'POST'])]
    public function modifyMember($memberId, MemberRepository $memberRepository, Request $request): Response
    {
        $member = $memberRepository->find($memberId);
        
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $formPinCode = $form->get('pincode')->getData();
            if($formPinCode !== null){
               $member->setPinCode($formPinCode); 
            }
            $memberRepository->add($member, true);
            $this->addFlash('success', 'Membre bien modifié');
            return $this->redirectToRoute('app_account_manage_members');
        }
        
        return $this->render('/Back/members/modify-member.html.twig', ['member' => $member, "form"=>$form]);
    }

    #[Route('/account/manage/members/delete/{memberId}', name: 'app_account_manage_delete_member', methods:['GET', 'POST'])]
    public function deleteMember($memberId, Request $request, MemberRepository $memberRepository, EntityManagerInterface $entityManager): Response
    {
        //! Same as the Front Controller method, need a rework, replace all information data by generic information and delete his timeSlots only, maybe add a field isActive on a member ?
        $member = $memberRepository->find($memberId);
        $slots = $member->getSlots();
        $messages = $member->getSent();

        if($request->isMethod('POST')){
            foreach ($slots as $slot) {
                $member->removeSlot($slot);
            }
            foreach ($messages as $message){
                $member->removeSent($message);
            }
            $entityManager->remove($member);
            $entityManager->flush();
    
            return $this->redirectToRoute('app_account_manage_member');
        }

        return $this->render('/Back/member/delete-member-confirmation.html.twig', ["member" => $member]);
    }
}