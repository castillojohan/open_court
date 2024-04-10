<?php

namespace App\Service;

use App\Entity\Member;
use App\Entity\Slot;
use App\Entity\User;
use App\Interface\TimeSlotRulesInterface;
use App\Repository\SlotRepository;
use App\Repository\UserRepository;
use DateTimeImmutable;

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
    private $maxUserDailyReservations = 4;
    // Reservation max journalière consécutive par tout les MEMBRES d'un USER
    private $maxUserDailyConsecutiveReservation = 2;
    // Reservation max par jour par MEMBRES
    private $maxMemberDailyReservation = 4;
    
    
    private $userRepository;
    private $slotRepository;

    public function __construct(UserRepository $userRepository, SlotRepository $slotRepository)
    {
        $this->userRepository = $userRepository;  
        $this->slotRepository = $slotRepository;  
    }

    public function canBookTimeSlot(Member $member): bool
    {
        return true;
    }

    public function hasRemainingWeeklyAvailableHours(User $userArg):bool 
    {
        $user = $this->userRepository->find($userArg);
        $members = $user->getMembers();
        $slotsInThisWeek = $this->slotRepository->findUserWeeklySlots($members);
        ($this->maxUserWeeklyreservations - count($slotsInThisWeek)) <= 0 
            ? $result = false 
            : $result = true ;
        return $result;
    }

    public function hasRemainingDailyAvailableHours(User $userArg): bool
    {
        $user = $this->userRepository->find($userArg->getId());
        $members = $user->getMembers();
        $dailyUserSlots = $this->slotRepository->findUserDailySlots($members);
        $this->maxUserDailyReservations - count($dailyUserSlots) <= 0
            ? $result = false
            : $result = true;
        return $result;
    }

    public function hasExceededDailyTime(Member $member): bool
    {
        $slots = $this->slotRepository->findMemberDailySlots($member->getId());
        $this->maxMemberDailyReservation - count($slots) <= 0
            ? $result = true
            : $result = false;
        return $result;
    }

    public function canBookConsecutivelyTimeSlots(User $userArg, Slot $newTimeSlot): bool
    {
        $user = $this->userRepository->find($userArg->getId());
        $members = $user->getMembers();
        $dailyUserSlots = $this->slotRepository->findUserDailySlots($members);

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


    /**
     * DevLog TODO.
     * Il semblerait que les trois methodes "has.." soit quasiement les même, reflechir à ça
     * la méthode canBookTimeSlot, doit se charger de vérifié les autres, et de charger des erreur si il y a,
     * éventuellement vérifié l'impact performance, entre methodes actuelle vs un chargement des données Users/Members/Slots 
     * depuis canBookTimeSlot()
     * Pour Jeudi 11 Avril,  
     * 1.Retourner les erreurs à l'utilisateur via le json du controller
     * 2.Modifier le JS pour traiter plusieurs erreurs
     */
}