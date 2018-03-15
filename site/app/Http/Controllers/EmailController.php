<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Mail;

class EmailController extends Controller {

    
    public function recebe_parametros($template, $assunto, $email, $name, $token){
        $this->envia_email($template, $assunto, $email, $name, $token);
    }

    private function envia_email($template, $assunto, $email, $name, $token){

        Mail::send( $template, ['token' => $token, 'name' => $name, 'email' => $email], function($message) use ($email, $name, $assunto) {
            $message->to($email, $name)->subject($assunto);
        });

    }
    
}