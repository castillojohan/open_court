<?php

namespace App\Service;

use DateTimeImmutable;

class StatService
{
    //! In case where season start and season end can be different from two associations structure, maybe i should create an Entity 'Season', which have 
    //! 'name', 'start_at', 'end_at' , and a relationship user_id fields for payment usecase ?

    public static function sortSlotByCourt($courts)
    {
        $slotsByCourt = [];
        foreach ($courts as $court) {
            $slotsByCourt[$court->getName()] = count($court->getSlots());
        }
        $courtOccupation = StatService::getCourtOccupation($slotsByCourt);
        return $courtOccupation;
    }

    public static function getCourtOccupation($slotsByCourt)
    {
        $dateNow = new DateTimeImmutable();
        $startSeason = $dateNow->setDate(2023, 9, 1);
        $diff = $startSeason->diff($dateNow);
        $totalPossibleOccupation = $diff->days * 15;
        $averageOccupation = [];
        foreach ($slotsByCourt as $court => $countOfSlot){
            $averageOccupation[$court] = round((($countOfSlot*100)/$totalPossibleOccupation), 1); 
        }
        return $averageOccupation;
    }
}