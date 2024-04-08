<?php

namespace App\Interface ;

use App\Entity\Member;
use App\Entity\User;

interface TimeSlotRulesInterface
{
    /** 
     * Get and calculate amount of hours remaining on current User, in this case, weekly.
     * 
     * @param User $user
     * @param integer $limit (momently raw data, next will coming from a DB rules)
     * @return boolean
     */
    public function getRemainingWeeklyAvailableHours(User $user): bool;

    /** 
     * Get and calculate amount of hours remaining on current User, in this case, daily.
     * 
     * @param User $user
     * @param integer $limit (momently raw data, next will coming from a DB rules)
     * @return boolean
     */
    public function getRemainingDailyAvailableHours(User $user): bool;

    /**
     * Verify if member can book a time slot , rules : max 2 hours/members/days 
     *
     * @param Member $member
     * @param integer $max
     * @return boolean
     */
    public function canBookTimeSlot(Member $member): bool ;

    /**
     * verify if current User can reserve multiple time slot consecutively 
     *
     * @param User $user
     * @param integer $max
     * @return boolean
     */
    public function canBookConsecutivelyTimeSlots(User $user): bool;
}