<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Hash;
use Validator;
use View;
use App\Pedido;
use App\Usuario;
use App\Entrega;

class PerfilController extends Controller {
 
 	public function index() {
        
        return View::make('perfil')->with(array( 
            'pedidos' => $this->pedidos(),
            'dados' => $this->dados_usuario(Auth::User()->id))
        );

 	}

    private function dados_usuario($id){

        $array = array();

        $dados_perfil = Usuario::where('id', Auth::user()->id)->first();

        $dados_entrega = Entrega::where('dados_cliente_id', Auth::user()->id)->first();

        $array = array(
            'perfil' => $dados_perfil,
            'entrega' => $dados_entrega
        );

        return $array;

    }

 	private function pedidos(){

 		$array = array();

 		$items = Pedido::where('cliente_id', Auth::user()->id)->orderBy('id', 'desc')->get();

        foreach ($items as $key => $value) {

            if(!empty($value['codigo_rastreio']) ? $rastreio = true : $rastreio = false);
            
            $array[$key] = array(
                'id' => $value['id'],
                'status' => $this->define_status($value['status']),
                'rastreio' => $rastreio,
                'codigo_rastreio' => $value['codigo_rastreio'],
                'codigo' => str_pad($value['id'], 5, '0', STR_PAD_LEFT),
                'endereco' => $value['endereco'],
                'numero' => $value['numero'],
                'cidade' => $value['cidade'],
                'uf' => $value['estado'],
                'data' => date('d/m/y', strtotime($value['created_at']))
            );

        }

        return $array;

 	}

    private function define_status($status){

        $array = array();

        if ($status == 0) {
            $array = array('nome' => 'Aguardando', 'class' => 'aguardando' );
        } else if ($status == 1) {
            $array = array('nome' => 'A caminho', 'class' => 'waiting' );
        } else if ($status == 2) {
            $array = array('nome' => 'Entregue', 'class' => 'done' );
        } else if ($status == 3) {
            $array = array('nome' => 'Cancelado', 'class' => 'cancel' );
        } else if ($status == 4) {
            $array = array('nome' => 'Atrasado', 'class' => 'late' );
        }

        return $array;

    }

    /* edição de dados */

    public function editar(Request $request){
        return $this->validate_editar_dados($request);
    }

    private function validate_editar_dados($request){

        $validator = Validator::make(
            [
                'nome' => $request['nome'],
                'email' => $request['email'],
                'senha_antiga' => $request['senha_antiga'],
                'senha_nova' => $request['senha_nova'],
                'cpf' => $request['cpf'],
                'telefone' => $request['telefone'],
                'dia' => $request['dia'],
                'mes' => $request['mes'],
                'ano' => $request['ano'],
                'cep' => $request['cep'],
                'numero' => $request['numero'],
                'endereco' => $request['endereco'],
                'bairro' => $request['bairro'],
                'lugar' => $request['lugar'],
            ],
            [
                'nome' => 'required|min:2|max:80',
                'email' => 'required|email',
                'senha_antiga' => 'sometimes|nullable|between:6,16',
                'senha_nova' => 'sometimes|nullable|between:6,16',
                'cpf' => 'required',
                'telefone' => 'required',
                'dia' => 'required',
                'mes' => 'required',
                'ano' => 'required',
                'cep' => 'required',
                'numero' => 'required|max:10',
                'endereco' => 'required',
                'bairro' => 'required',
                'lugar' => 'required',
            ]
        );

        if (count($validator->errors()->all()) == 0) {
            
            if ($this->check_email($request['email']) == true) {
                $status = 502;
            } else {

                /* adaptação para se um dos dois campos ou senha antiga e senha nova não forem preechidos.. caso alguma tenha sido */

                if (!empty($request['senha_antiga']) and empty($request['senha_nova'])) {
                    return 503;
                } else if (empty($request['senha_antiga']) and !empty($request['senha_nova'])){
                    return 503;
                }

                DB::beginTransaction();

                try {

                    $data = array(
                        'nome_completo' => $request['nome'],
                        'email' => $request['email'],
                        'cpf' => $request['cpf'],
                        'telefone' => $request['telefone'],
                        'dia_nascimento' => $request['dia'],
                        'mes_nascimento' => $request['mes'],
                        'ano_nascimento' => $request['ano']
                    );

                    $data_entrega = array(
                        'cep' => $request['cep'],
                        'endereco' => $request['endereco'],
                        'bairro' => $request['bairro'],
                        'complemento' => $request['complemento'],
                        'lugar' => $request['lugar'],
                        'numero' => $request['numero']
                    );

                    if (!empty($request['senha_antiga'])) {
                        
                        $checa_senha = $this->checa_senha($request['senha_antiga'], $request['email']);

                        if ($checa_senha == true) {
                            $data['password'] = Hash::make($request['senha_nova']);
                        } else {
                            return 504;
                        }
                        
                    }

                    Usuario::where('id', Auth::User()->id)->update($data);

                    Entrega::where('dados_cliente_id', Auth::User()->id)->update($data_entrega);

                    $status = 200;

                    DB::commit();

                } catch (Exception $e) {

                    $status = 500;

                    DB::rollback();

                }

            }

        } else {
            $status = 501;
        }

        return $status;

    }

    private function check_email($email){

        $qry = Usuario::where('email', $email)->first();

        if (count($qry) > 0) {
            
            if(Auth::User()->id == $qry->id){
                return false;
            } else {
                return true;
            }

        } else {
            return false;
        }

    }

    private function checa_senha($senha, $email){

        $usuario = auth()->guard('web');

        if ($usuario->attempt(['email' => $email, 'password' => $senha])) {
            return true;
        } else {
            return false;
        }

    }

}
