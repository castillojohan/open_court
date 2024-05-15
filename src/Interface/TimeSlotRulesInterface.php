<?php

namespace App\Interface ;

use App\Entity\Member;
use App\Entity\Slot;
use App\Entity\User;
use Doctrine\Common\Collections\Collection;

interface TimeSlotRulesInterface
{
    /** 
     * Get and calculate amount of hours remaining on current User, in this case, weekly.
     * 
     * @param User $user
     * @return boolean
     */
    public function hasRemainingWeeklyAvailableHours(User $user, Collection $members): bool;

    /** 
     * Get and calculate amount of hours remaining on current User, in this case, daily.
     * 
     * @param User $user
     * @return boolean
     */
    public function hasRemainingDailyAvailableHours(User $user, Collection $dailyUserSlots): bool;

    /**
     * verify if current User can reserve multiple time slot consecutively 
     *
     * @param User $user
     * @return boolean
     */
    public function canBookConsecutivelyTimeSlots(User $user, Slot $slot, Collection $dailyUserSlots): bool;

    /**
     * Verify if an member wich book a time slot has reached is capacity, capacity defined by administrators 
     *
     * @param Member $member
     * @param Slot $slot
     * @return boolean true if reached|false if unreached and can book
     */
    public function hasExceededDailyTime(Member $member, Slot $slot): bool;
}