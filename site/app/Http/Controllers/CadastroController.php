<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App;
use Auth;
use DB;
use Hash;
use Validator;
use View;
use App\Usuario;

class CadastroController extends Controller {

	public function cadastro(Request $request){

        return $this->validate_cadastro($request);

    }

    private function validate_cadastro($request){

        $validator = Validator::make(
            [
                'nome_completo' => $request['nome_completo'],
                'email' => $request['email'],
                'senha' => $request['senha']
            ],
            [
                'nome_completo' => 'required|min:2|max:100',
                'email' => 'required|email',
                'senha' => 'required|min:6|max:16',
            ]
        );

        if (count($validator->errors()->all()) == 0) {

            $request['token'] = Hash::make('d/m/y h:i:s');
            $request['password'] = Hash::make($request['senha']);
            $request['ip'] = getenv("REMOTE_ADDR");

            if ($this->check_email($request['email']) == true){

                DB::beginTransaction();

                try {

                    $usuario = new Usuario;

                    $usuario->fill( $request-> all() );
                    $usuario->save();

                    DB::commit();

                    $checagem = true;

                    $status = 200;

                } catch (Exception $e) {

                    $checagem = false;

                    $status = 500;

                    DB::rollback();

                }

                if ($checagem == true) {

                    $this->fazer_login($request['email'], $request['senha']);

                    /*(new EmailController)->recebe_parametros(
                        'layouts.email_cadastro', 
                        'Confirmação de cadastro', 
                        $request['email'], 
                        $request['nome']
                    );*/

                }

            } else {
                $status = 502;
            }

        
        } else {
            $status = 501;
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

    private function check_email($email){

        $qry = Usuario::where('email', $email)->first();

        if (count($qry) <= 0) {
            return true;
        } else {
            return false;
        }

    }
 
}
