<?php

namespace App\Helpers;
use App\Models\User;

class Helpers{

   static public function generateAuthToken(User $user){
        $auth_token = '';

        if($user){
            $auth_token = $user->name . $user->email. date('today');
            return $auth_token;
        }
    }
}

?>
