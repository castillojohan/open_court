<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Repository\MemberRepository;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageController extends AbstractController
{
    #[Route('/account/message', name: 'app_account_message')]
    public function messaging(MemberRepository $memberRepository, Request $request): Response
    {
        $sessionMember = $request->getSession()->get('member');
        $currentMember = $memberRepository->find($sessionMember);
        $memberList = $memberRepository->getMemberForMessaging();
        return $this->render('./Front/messaging.html.twig', ["member"=>$currentMember, "members"=>$memberList]);
    }

    #[Route('/account/message/create', name: 'app_account_message_create')]
    public function createMessage(MemberRepository $memberRepository): JsonResponse
    {
        $memberList = $memberRepository->getMemberForMessaging();
        return $this->json(
            [ "members"=> $memberList ],
            Response::HTTP_ACCEPTED,
            [],
            ["groups"=> "get_member"]
        );
    }

    #[Route('/account/conversations/{id}', name:'app_account_conversations', methods: 'GET')]
    public function getConversation(Member $paramMemberId, MessageRepository $messageRepository, Request $request, MemberRepository $memberRepository): JsonResponse
    {
        $memberInRequest = $request->getSession()->get('member');
        $currentMember = $memberRepository->find($memberInRequest);

        $messages = $messageRepository->findConversationBetweenMembers($currentMember->getId(), $paramMemberId->getId());

        return $this->json(
            ["messages" => $messages],
            Response::HTTP_ACCEPTED,
            [],
            ["groups"=>"get_conversation"]
        );
    }
}