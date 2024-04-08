<?php

namespace App\Service;

use App\Entity\Member;
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
    private $maxWeeklyreservations = 8;
    // Reservation max journalière par tout les MEMBRES d'un USER
    private $maxDailyreservations = 6;
    // Reservation max journalière consécutive par tout les MEMBRE d'un USER
    private $maxDailyConsecutiveReservation = 2;
    // Reservation max journalière par un membre
    
    private $userRepository;
    private $slotRepository;

    public function __construct(UserRepository $userRepository, SlotRepository $slotRepository)
    {
        $this->userRepository = $userRepository;  
        $this->slotRepository = $slotRepository;  
    }

    public function defineWeek()
    {
        $today = new DateTimeImmutable();
        $weekStartAt = $today->modify('monday this week');
        $weekEndAt = $today->modify('sunday this week');
        return [$weekStartAt, $weekEndAt];
    }

    public function getRemainingWeeklyAvailableHours(User $userArg):bool 
    {
        $user = $this->userRepository->find($userArg);
        $members = $user->getMembers();
        $slotsInThisWeek = $this->slotRepository->findWeeklySlots($members);
        if(($this->maxWeeklyreservations - count($slotsInThisWeek)) <= 0){
            return false;
        }
        return true;
    }

    public function getRemainingDailyAvailableHours(User $user): bool
    {
        return true;
    }

    public function canBookTimeSlot(Member $member): bool
    {
        return true;
    }

    public function canBookConsecutivelyTimeSlots(User $user): bool
    {
        return true;
    }


    /**
     * DevLog TODO.
     * Pour Mardi 8 Avril,  
     * 1.Finir l'implémentation des règles métiers
     * 2.Imaginer dans ce service une méthode qui permettrait sur un simple appel
     * de vérifier si les règles sont respectées.
     * 3.Retourner les erreurs à l'utilisateur via le json du controller
     * 4.Modifier le JS pour traiter plusieurs erreurs
     */
}