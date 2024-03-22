<?php

namespace App\Controller\Front;

use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    #[Route('/account/lessons', name:'app_lessons', methods: 'GET')]
    public function getLessons(LessonRepository $lessonRepository, Request $request): Response
    {
        $member = $request->getSession()->get('member');
        return $this->render('/Front/lesson.html.twig', ['lessons'=>$lessonRepository->findAll(), 'member'=> $member]);
    }
}