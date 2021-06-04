<?php

namespace App\Helpers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class Helpers{

   static public function generateUserToken(){
    return Str::random(40);
    }
}

?>
