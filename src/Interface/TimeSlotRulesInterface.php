<?php

namespace App\Interface ;

use App\Entity\Member;
use App\Entity\Slot;
use App\Entity\User;

interface TimeSlotRulesInterface
{
    /** 
     * Get and calculate amount of hours remaining on current User, in this case, weekly.
     * 
     * @param User $user
     * @return boolean
     */
    public function hasRemainingWeeklyAvailableHours(User $user): bool;

    /** 
     * Get and calculate amount of hours remaining on current User, in this case, daily.
     * 
     * @param User $user
     * @return boolean
     */
    public function hasRemainingDailyAvailableHours(User $user): bool;

    /**
     * Verify if member can book a time slot , rules : max 2 hours/members/days 
     *
     * @param Member $member
     * @return boolean
     */
    public function canBookTimeSlot(Member $member): bool ;

    /**
     * verify if current User can reserve multiple time slot consecutively 
     *
     * @param User $user
     * @return boolean
     */
    public function canBookConsecutivelyTimeSlots(User $user, Slot $slot): bool;

    /**
     * Verify if an member wich book a time slot has reached is capacity, capacity defined by administrators 
     *
     * @param Member $member
     * @return boolean true if reached|false if unreached and can book
     */
    public function hasExceededDailyTime(Member $member): bool;
}