<?php

namespace App\Controller\Front;

use App\Entity\Member;
use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MemberRepository;
use App\Repository\MessageRepository;
use App\Service\MessageService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Utils\Utils;

class MessageController extends AbstractController
{
    #[Route('/account/message', name: 'app_account_message')]
    public function messaging(MemberRepository $memberRepository, Request $request, MessageRepository $messageRepository, MessageService $messageService): Response
    {
        $sessionMember = $request->getSession()->get('member');
        $currentMember = $memberRepository->find($sessionMember);

        // pass through child firewall, if current user is minor, firewall throw Denied exception else, nothing happen.
        Utils::childFirewall($currentMember);

        $conversations = $messageRepository->findConversationsWithMemberId($currentMember);
        $sortedConversations = $messageService->sortMessages($conversations, $currentMember);
        return $this->render('./Front/messaging.html.twig', ["currentMember"=>$currentMember, "conversations" => $sortedConversations]);
    }

    #[Route('/account/message/create', name: 'app_account_message_create', methods:['GET', 'POST'])]
    public function createMessage(MemberRepository $memberRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $currentMember = $request->getSession()->get('member');
        $member = $memberRepository->find($currentMember);

        Utils::childFirewall($currentMember);

        $membersList = $memberRepository->getMemberForMessaging($member->getId());
        if($request->isMethod('GET')){
            return $this->render('Front/messaging-create.html.twig', ["members" => $membersList]);
        }

        $submittedToken = $request->getPayload()->get('_token');
        if($this->isCsrfTokenValid('send', $submittedToken)){
            $payload = $request->getPayload()->all();
            $messageToSend = new Message();
            $messageToSend->setSender($member);
            $form = $this->createForm(MessageType::class, $messageToSend);
            
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                $entityManager->persist($messageToSend);
                $entityManager->flush();
                return $this->redirectToRoute('app_account_message');
            }
            $membersList = $memberRepository->find($payload['message']['recipient']);
            $this->addFlash('error', 'Une erreur est survenue..');
            return $this->render('Front/messaging-create.html.twig', ["members" => [$membersList]], new Response('', 422));
        }
        $this->addFlash('error', 'Token Invalide, contactez un administrateur.');
        return $this->render('Front/messaging-create.html.twig', ["members" => $membersList], new Response('', 422));
    }

    #[Route('/account/message/send/{recipient}', name:'app_account_message_send', methods: ['POST'])]
    public function sendMessageToRecipient($recipient, Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator, MemberRepository $memberRepository, HubInterface $hub): JsonResponse
    {

        $member = $memberRepository->find($request->getSession()->get('member'));
        $message = new Message();
        $message->setSender($member);

        $tokenFromRequest = $request->headers->get('x-csrf-token');
        
        // case where bad token
        if(!$this->isCsrfTokenValid('send-message', $tokenFromRequest)){
            return $this->json(['error'=>'Il y à un problème avec le token, contactez un Admin.'], Response::HTTP_UNAUTHORIZED);
        }

        $json = json_decode($request->getContent(), true);
        $recipientIdFromRequest = $json['recipient'];
        $messageContentFromRequest = $json['content'];
        // check if, recipient ID from request is the same as recipient into route parameters.
        if($recipientIdFromRequest != $recipient){
            return $this->json(
                ["error" => "Une erreur s'est produite"],
                Response::HTTP_BAD_REQUEST,
                [],
                [],
            );
        }
        // to be aware must instanciate a new Member() for recipient..
        $recipient = $memberRepository->find($recipientIdFromRequest);
        $message->setSender($member)
            ->setRecipient($recipient)
            ->setContent($messageContentFromRequest)
        ;
        $errors = $validator->validate($message);
        $errorsMessages = [];
        foreach ($errors as $error) {
            $errorsMessages[$error->getPropertyPath()] = $error->getMessage();
        }

        if(count($errors) > 0){
            return new JsonResponse(['errors'=>$errorsMessages], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $entityManager->persist($message);
        $entityManager->flush();

        $update = new Update(
            ['send-message'],
            json_encode(['message' => 
                [
                    "date"=>$message->getCreatedAt(),
                    "sender"=>[
                        $message->getSender()->getId(),
                        $message->getSender()->getFirstName(),
                    ],
                    "recipient" => [
                        $message->getRecipient()->getId(),
                        $message->getRecipient()->getFirstName(),
                    ],
                    "content" => $message->getContent(),
                ]
            ])
        );
        $hub->publish($update);
        
        return $this->json(
        [
            'success'=>['Message envoyé'],
            'message' => $message
        ],
            Response::HTTP_OK,
            [],
            ['groups'=>'get_conversation']
        );
    }

    #[Route('/account/message/read/{recipient}', name:'app_account_message_read', methods: ['GET'])]
    public function readMessageFromRecipient($recipient, Request $request, MessageRepository $messageRepository, EntityManagerInterface $entityManager): JsonResponse
    {
        $messages = $messageRepository->findUnreadMessagesBetweenMembers($request->getSession()->get('member'), $recipient);
        foreach ($messages as $message) {
            $message->readMessage();
            $entityManager->persist($message);
        }
        $entityManager->flush();

        return $this->json(
            ['data'=>'messages marked as read'],
            Response::HTTP_OK,
            [],
            []
        );
    }
}