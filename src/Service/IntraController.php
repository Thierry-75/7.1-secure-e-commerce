<?php

namespace App\Service;


class IntraController
{
    
    static function confirmationEmail($user)
    {
        if(!$user == null){
            if($user->isVerified()  == false){
                return true;
            }
        }
    }

    static function completeCoordonnees($user)
    {
        if(!$user == null){
            if($user->isVerified() === true && $user->isCompleted() === false){
                return true;
            }
        }
    }

}
