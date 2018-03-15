<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use DB;
use Auth;
use Session;
use Mail;
use Validator;
use App\Usuario;

class LoginController extends Controller {

	public function login(Request $request){

        return $this->validate_login($request);

    }

    private function validate_login($request){

        $usuario = null;

        if (!empty($request->input('email')) and !empty($request->input('senha'))) {

            $qry = Db::table('dados_cliente')->where('email', '=', $request['email'])->first();

            if (!empty($qry)) {

                $check_ativo = $qry->status;

                if ($check_ativo == 1) {

                    try {

                        $usuario = auth()->guard('web');

                        if ($usuario->attempt(['email' => $request->input('email'), 'password' => $request->input('senha')])) {
                            
                            $usuario = Auth::user()->nome_completo;
                            $status = 200;

                        } else {
                            $status = 501;
                        }

                    } catch (Exception $e) {
                        $status = 500;
                    }

                } else {
                    $status = 503;
                }                

            } else {
                $status = 501;
            }
            
        } else {
            $status = 502;
        }

        return array('status' => $status, 'usuario' => $usuario);

    }


    public function recuperar(Request $request){

        return $this->validate_recuperar($request);

    }

    private function validate_recuperar($request){

        if (!empty($request->input('email'))) {

            $usuario = Usuario::where('email', $request->input('email'))->first();

            if (!empty($usuario)) {

                (new EmailController)->recebe_parametros(
                    'emails.recuperar', 
                    'Recuperação de senha', 
                    $request->input('email'), 
                    $usuario->nome,
                    $usuario->token
                );

                $status = 200;

            } else {
                $status = 503;
            }

        } else {
            $status = 502;
        }

        return $status;

    }

    public function reset_view(Request $request){

        
        if(Auth::check() != true) {

            if (!empty($request['email']) or !empty($request['tk'])) {

                $qry = Usuario::where('email', $request['email'] )->where('token', $request['tk'] )->first();

                if (!empty($qry)) {
                    return view('reset');
                } else {
                    return redirect('/');
                }

            } else {
                return redirect('/');
            }

        } else {
            return redirect('/');
        }

    }

    public function reset(Request $request){

        return $this->validate_reset($request);

    }

    private function validate_reset($request){
        
        if (!empty($request->input('senha_nova')) || !empty($request->input('confirmar_senha')) ) {

            if (strlen( $request->input('senha_nova')) < 6 ) {
                $status = 504;
            } else if ( strlen( $request->input('senha_nova')) > 16) {
                $status = 504;
            } else if ( $request->input('senha_nova') != $request->input('confirmar_senha' )) {
                $status = 505;
            } else {

                $qry = Usuario::where('email', $request->input('email') )->where('token', $request->input('token') )->first();

                if ( !empty( $qry ) ) {

                    $password = $request->input('senha_nova');

                    Usuario::where('email', $request->input('email') )->update( array (
                        'password' => bcrypt($password), 
                        'token' => bcrypt(date('d/m/y h:i:s')) )
                    );

                    $this->fazer_login($request->input('email'), $password);

                    $status = 200;

                } else {

                    $status = 503;
                }
            }
            
        } else {
            $status = 502;
        }

        return $status;
    
    }

    private function fazer_login($email, $senha){
        
        $usuario = auth()->guard('web');

        if ($usuario->attempt(['email' => $email, 'password' => $senha])) {
            return true;
        } else {
            return false;
        }

    }

    public function logout(){
       
        Auth::logout();

        Session::flush();
        
        return redirect('/');
    
    }
 
}
