<?php

namespace App\Http\Controllers;

use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    static public function sendRegisterMail(User $user){
        $details = [
            'title'=> 'Dokumentator Register',
            'body'=> 'This is mail from dokumentator to register account',
        ];

        Mail::to($user->email)->send(new RegisterMail($details));
    }

    static public function sendAuthorisationDeviceMail(User $user){
        $details = [
            'title'=> 'Dokumentator Device Authorisation',
            'body'=> 'This is mail from dokumentator to authorisation device',
        ];

        Mail::to($user->email)->send(new RegisterMail($details));
    }
}
