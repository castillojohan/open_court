<?php

namespace App\Controller\Front;

use App\Entity\Lesson;
use App\Repository\LessonRepository;
use App\Repository\MemberRepository;
use App\Repository\SlotRepository;
use App\Service\LessonService;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    #[Route('/account/lessons', name:'app_lessons', methods: 'GET')]
    public function getLessons(LessonRepository $lessonRepository, Request $request, LessonService $lessonService): Response
    {
        $lessons = $lessonRepository->getNextLesson();
        $lessonService->calculateRemaining($lessons);
        $member = $request->getSession()->get('member');
        return $this->render('/Front/lesson.html.twig', ['lessons'=>$lessons, 'member'=> $member]);
    }

    #[Route('/account/lesson/{lessonFromRequest}/subscribe', name:'app_lesson_subscribe', methods:'GET')]
    public function enrolLesson(Request $request, Lesson $lessonFromRequest, LessonRepository $lessonRepository, MemberRepository $memberRepository, EntityManagerInterface $entityManager, LessonService $lessonService)
    {
        if($lessonFromRequest == null){
            throw $this->createNotFoundException("Ressource non trouvée");
        }
        if($request->getSession()->get('member') == null){
            throw $this->createAccessDeniedException('Vous devez avoir un membre connecté');
        }

        $memberFromSession = $request->getSession()->get('member');
        $member = $memberRepository->find($memberFromSession);
        $lesson = $lessonRepository->find($lessonFromRequest);

        if($lessonService->getDisponibility($lesson)){
            $this->addFlash('error', "Le cours choisi est complet.");
            return $this->redirectToRoute('app_lessons');
        }
        $slots = $lesson->getSlots();
        $startAt = $lesson->getSlots()[0]->getStartAt();

        count($slots) < 1 
            ? $endAt = $lesson->getSlots()[0]->getEndAt() 
            : $endAt = $lesson->getSlots()[count($slots)-1]->getEndAt();

        $successSentence = "Inscription pour à: {$lesson->getNAme()}, {$startAt->format('d-m-Y')} de {$startAt->format('H:i')} à {$endAt->format('H:i')}";
        $lesson->addLessonMember($member);
        
        $entityManager->persist($lesson);
        $entityManager->flush();
        $this->addFlash("success", $successSentence);

        return $this->redirectToRoute('app_lessons', []);
    }

    #[Route('/account/lesson/{lessonFromRequest}/unsubscribe', name:'app_lesson_unsubscribe'), ]
    public function unsubscribe(MemberRepository $memberRepository, Lesson $lessonFromRequest, LessonRepository $lessonRepository, Request $request, EntityManagerInterface $entityManager)
    {
        if($lessonFromRequest == null){
            throw $this->createNotFoundException("Ressource non trouvée");
        }
        if($request->getSession()->get('member') == null){
            throw $this->createAccessDeniedException('Vous devez avoir un membre connecté');
        }

        $lesson = $lessonRepository->find($lessonFromRequest);
        $memberFromSession = $memberFromSession = $request->getSession()->get('member');
        $member = $memberRepository->find($memberFromSession);

        $lessonStartAt = $lesson->getSlots()[0]->getStartAt()->format('d-m-Y H:i');
        count($lesson->getSlots()) < 1 
            ? $endAt = $lesson->getSlots()[0]->getEndAt() 
            : $endAt = $lesson->getSlots()[count($lesson->getSlots())-1]->getEndAt();
        $endAt = $endAt->format('H:i');
        
        $lesson->removeLessonMember($member);
        $entityManager->persist($lesson);
        $entityManager->flush();

        $this->addFlash('success', "Désinscription du: {$lesson->getName()}, {$lessonStartAt}-{$endAt}");
        return $this->redirectToRoute('app_lessons');
    }
}