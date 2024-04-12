<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\Slot;
use App\Entity\User;
use App\Interface\TimeSlotRulesInterface;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;

class TimeSlotRules implements TimeSlotRulesInterface
{
    /*
    Note à moi même, imaginons, une table qui contiendrais les règles métiers, modifiable
    par l'administrateur de l'association? au lieu d'insrire en dur dans chaques utilisateur 
    une valeur de "slots" maximum, il me suffit d'avoir une entité Rules qui aurait un field
    maxWeeklyReservation = 6 par exemple , ce service pourrais avoir les "règles" directement en 
    en propriétées ? 
    */

    //* Ces données viendront ultérieurment d'une table Rules
    // Reservations max hebdomadaire par tout les MEMBRES d'un USER
    private $maxUserWeeklyreservations = 8;
    // Reservation max journalière par tout les MEMBRES d'un USER
    private $maxUserDailyReservations = 3;
    // Reservation max journalière consécutive par tout les MEMBRES d'un USER
    private $maxUserDailyConsecutiveReservation = 2;
    // Reservation max par jour par MEMBRES
    private $maxMemberDailyReservation = 3;
    
    
    private $userRepository;
    private $slotRepository;

    public function __construct(UserRepository $userRepository, SlotRepository $slotRepository)
    {
        $this->userRepository = $userRepository;  
        $this->slotRepository = $slotRepository;  
    }

    public function canBookTimeSlot(Member $memberArg, User $userArg, Slot $slotArg): array
    {
        $user = $this->userRepository->find($userArg);
        $member = $memberArg;
        $members = $user->getMembers();
        $userDailySlots = $this->slotRepository->findUserDailySlots($members, $slotArg);
        $slot = $slotArg;

        $errors = [];
        $getErrors = false;

        // si false, on génère l'erreur
        if(!$this->hasRemainingWeeklyAvailableHours($user, $members)){
            $errors['error'][] = "Vous n'avez plus de temps restant pour la semaine";
            $getErrors = true;
        }
        
        //si false, on génère l'erreur
        if(!$this->hasRemainingDailyAvailableHours($user, $members)){
            $errors['error'][] = "Le compte a épuisé son nombre de réservation journalière";
            $getErrors = true;
        }

        // si true , on génère l'erreur
        if($this->hasExceededDailyTime($member, $slot)){
            $errors['error'][] = "Vous n'avez plus de temps restant pour la journée";
            $getErrors = true;
        }

        // si false, on génère l'erreur
        if(!$this->canBookConsecutivelyTimeSlots($user, $slot, $userDailySlots)){
            $errors['error'][] = "Vous ne pouvez pas reserver plus de {$this->maxUserDailyConsecutiveReservation} heures consécutives";
            $getErrors = true;
        }
        
        $result[] = $getErrors;
        $result[] = $errors; 
        return $result;
    }

    // Est ce qu'il reste au COMPTE USER des heures hebdomadaire disponible ? OUI return TRUE | NON return FALSE
    public function hasRemainingWeeklyAvailableHours($userArg, $members): bool
    {
        $slotsInThisWeek = $this->slotRepository->findUserWeeklySlots($members);
        ($this->maxUserWeeklyreservations - count($slotsInThisWeek)) > 0 
            ? $result = true 
            : $result = false ;
        return $result;
    }

    // Est ce qu'il reste au COMPTE USER des heures de reservations journalière ? OUI return TRUE | NON return FALSE
    public function hasRemainingDailyAvailableHours($userArg, $dailyUserSlots): bool
    {
        $this->maxUserDailyReservations > count($dailyUserSlots) 
            ? $result = true
            : $result = false;
        return $result;
    }

    // Est ce que le MEMBRE à dépassé son temps de reservations impartis journalier ? OUI return TRUE | NON return FALSE
    public function hasExceededDailyTime($member, $slot): bool
    {
        $slots = $this->slotRepository->findMemberDailySlots($member->getId(), $slot);
        count($slots) > $this->maxMemberDailyReservation
            ? $result = true
            : $result = false;
        return $result;
    }

    // Le COMPTE USER peut t'il reserver consecutivement un autre TIMESLOT ? OUI return TRUE | NON return FALSE
    public function canBookConsecutivelyTimeSlots($userArg, $newTimeSlot, $dailyUserSlots): bool
    {
        $previousTimeSlot = null;
        $arrayConsecutiveTimeSlot = [];
        foreach ($dailyUserSlots as $timeSlot) {
            if($previousTimeSlot !== null && $timeSlot->getStartAt() == $previousTimeSlot->getEndAt()){
                $arrayConsecutiveTimeSlot[] = $timeSlot;
                $arrayConsecutiveTimeSlot[] = $previousTimeSlot;
                if(count($arrayConsecutiveTimeSlot) >= $this->maxUserDailyConsecutiveReservation){
                    if($timeSlot->getEndAt() == $newTimeSlot->getStartAt() || $previousTimeSlot->getStartAt() == $newTimeSlot->getEndAt()){
                        return false;
                    }
                }
            }
            $previousTimeSlot = $timeSlot;
        }
        return true;
    }

}