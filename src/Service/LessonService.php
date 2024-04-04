<?php

namespace App\Service;

use App\Entity\Lesson;

class LessonService
{
    /**
     * Update remaining lessons places 
     *
     * @param Lesson[] $lessons
     * @return void
     */
    public function calculateRemaining(array $lessons)
    {
        foreach ($lessons as $lesson) {
            $membersEnrolled = count($lesson->getLessonMember());
            $actualCapacity = $lesson->getCapacity() - $membersEnrolled;
            $lesson->setActualCapacity($actualCapacity);
        }
    }

    public function getDisponibility(Lesson $lesson)
    {
        $lesson->getActualCapacity() > 0 
            ? $disponibility = true
            : $disponibility = false  
        ;
        return $disponibility;
    }
}