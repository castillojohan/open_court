<?php

namespace App\Service;

class MessageService
{
    public function sortMessages($messagesToSort, $currentMember)
    {
        $conversations = [];
        foreach ($messagesToSort as $message) {
            $sender = $message->getSender();
            $recipient = $message->getRecipient();
            
            // Si l'utilisateur courant est l'expéditeur, ajoutez le message à la conversation du destinataire
            if ($sender === $currentMember) {
                $recipientName = "{$recipient->getId()}/{$recipient->getFirstName()}";
                if (!isset($conversations[$recipientName])) {
                    $conversations[$recipientName] = [];
                }
                $conversations[$recipientName][] = $message;
            } 
            // Si l'utilisateur courant est le destinataire, ajoutez le message à la conversation de l'expéditeur
            elseif ($recipient === $currentMember) {
                $senderName = "{$sender->getId()}/{$sender->getFirstName()}";
                if (!isset($conversations[$senderName])) {
                    $conversations[$senderName] = [];
                }
                $conversations[$senderName][] = $message;
            }
        }
        return $conversations;
    }
}