<?php

namespace App\Utils ;

use Exception;

class Utils {

    public static function childFirewall($member){
        if($member->getAge() < 18){
            throw new Exception("Malheuresement l'accès à cette zone ne vous est pas accessible.", 403);
        }
    }

    /**
     * function to control 404/403 exception
     * @param Member $member (memberID from route parameter)
     * @param User $user (current User)
     * @param Member|null $currentMember ($current member from session, if exist|if needed)
     * 
     * @return throw Exception (404/403) 
     */
    public static function controlException($member, $user, $currentMember=null)
    {
        //dump($currentMember->getAge() < 18 && $member->getAge() < 18);
        switch (true) {
            case $member->getId() == null:
                throw new Exception("Ressource non trouvée", 404);
                break;
            case $member->getUser() !== $user:
                throw new Exception("Cette ressource ne vous appartiens pas", 403);
                break;
            // in this case, check if member to modify is the same as the current member and if the member to modify isn't minor ( case which minor can't delete or modify profil ) 
            case ($currentMember && $currentMember->getAge() < 18):
                throw new Exception("Vous ne pouvez pas accéder a cette ressource", 403);
                break;
            // case : Parent can't change profil of his husband, but can change profil of his minor child.
            case ($currentMember && $member->getUser() === $user && $member->getAge() >=18 && $member->getId() !== $currentMember->getId()):
                throw new Exception("Vous ne pouvez pas accéder a cette ressource", 403);
                break;
        }
    }
}