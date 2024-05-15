<?php

namespace App\Controller\Publish;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Annotation\Route;

class PublishController
{
    #[Route('/publish', name: 'app_publish')]
    public function publish(HubInterface $hub): Response
    {
        $update = new Update(
            'http://127.0.0.1:8000/book-slot',
            json_encode(['status' => 'created'])
        );
        $hub->publish($update);

        return new Response('published!');
    }
}

