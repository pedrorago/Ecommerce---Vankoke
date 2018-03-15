<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Correios;
use DB;
use Validator;
use View;
use App\Cupons;
use App\Usuario;


class CheckoutController extends Controller {
 	
 	public function index() {

 		if (isset($_SESSION['produtos']['checkout'])) {
 			
 			if ($_SESSION['produtos']['checkout'] == true) {
 				
 				return View('checkout')->with(array(
 					'carrinho' => $_SESSION['produtos'],
 					'endereco' => $_SESSION['endereco']
 				));

 			} else {
 			    return redirect('/');
 		    }

 		} else {
 			return redirect('/');
 		}
 		
 	}

 	public function cupom_promocional(Request $request){

 		$valor = null;

 		$total = null;

 		$cupom = strtoupper($request['cupom']);

 		if (empty($cupom)) {
 			$status = 501;
 		} else if($_SESSION['produtos']['frete']['check'] == false){
            $status = 502;
        } else {

 			$cupom = Cupons::where('codigo', $cupom)->where('status', 1)->first();

 			if (!empty($cupom)) {
 				
 				$status = 200;

 				if (isset($_SESSION['produtos'])) {
 					$total_checkout = $_SESSION['produtos']['total_double'];
 				}

 				// obter a taxa de desconto de acordo com a porcentagem

 				$total = ( 1 - ($cupom['porcentagem'] / 100) ) * $total_checkout;

 				$desconto = $total_checkout - $total;

 				$valor_desconto = 'R$ '.number_format($desconto, 2, ',', '.');

 				$valor_total = 'R$ '.number_format($total, 2, ',', '.');

 				// atualiza na sessão o desconto

 				if (!isset($_SESSION['produtos']['cupom'])) {
 					$_SESSION['produtos']['cupom']['check'] = true;
 					$_SESSION['produtos']['cupom']['valor'] = $desconto;
 				} else {
 					$_SESSION['produtos']['cupom']['check'] = true;
 					$_SESSION['produtos']['cupom']['valor'] = $desconto;
 				}

 			} else {
 				$status = 501;
 			}

 		}

 		return $array = array('status' => $status, 'valor' => $valor_desconto, 'total' => $valor_total);

 	}

 	public function salvar_dados(Request $request){

 		if (Auth::check() == true) {
 			$retorna = $this->validate_editar_dados($request);
 		} else {
 			$retorna = $this->criar_usuario($request);
 		}

 		return $retorna;

 	}

 	public function validate_editar_dados($request){

 		$validator = Validator::make(
            [
                'nome' => $request['nome'],
                'email' => $request['email'],
                'cpf' => $request['cpf'],
                'telefone' => $request['telefone'],
                'dia' => $request['dia'],
                'mes' => $request['mes'],
                'ano' => $request['ano']
            ],
            [
                'nome' => 'required|min:2|max:80',
                'email' => 'required|email',
                'cpf' => 'required',
                'telefone' => 'required',
                'dia' => 'required',
                'mes' => 'required',
                'ano' => 'required'
            ]
        );

        if (count($validator->errors()->all()) == 0) {
            
            if ($this->check_email($request['email']) == true) {
                $status = 502;
            } else {            

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

                    Usuario::where('id', Auth::User()->id)->update($data);

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

 	public function criar_usuario($request){

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

    public function checar_entrega(Request $request){

    	$cep = $request['cep'];

    	$cep = str_replace('-', '', $cep);

        $checa_cep = Correios::cep($cep);

        if (!empty($checa_cep)) {
            
            if (isset($request['tipo'])) {
                
                if ($request['tipo'] == 'normal') {

                    $_SESSION['produtos']['frete']['valor_frete_selecionado'] = $_SESSION['produtos']['frete']['valor_pac'];
                    $_SESSION['produtos']['frete']['valor_frete_formatado'] = $_SESSION['produtos']['frete']['valor_pac_formatado'];
                    $_SESSION['produtos']['frete']['prazo_frete'] = $_SESSION['produtos']['frete']['prazo_pac'];
                    $_SESSION['produtos']['frete']['tipo_frete_selecionado'] = 'normal';
                    
                } else {

                    $_SESSION['produtos']['frete']['valor_frete_selecionado'] = $_SESSION['produtos']['frete']['valor_sedex'];
                    $_SESSION['produtos']['frete']['valor_frete_formatado'] = $_SESSION['produtos']['frete']['valor_sedex_formatado'];
                    $_SESSION['produtos']['frete']['prazo_frete'] = $_SESSION['produtos']['frete']['prazo_sedex'];
                    $_SESSION['produtos']['frete']['tipo_frete_selecionado'] = 'expresso';

                }

                $_SESSION['endereco'] = array(
                    'check' => true,
                    'cep' => $_SESSION['endereco']['cep'],
                    'logradouro' => $checa_cep['logradouro'],
                    'bairro' => $checa_cep['bairro'],
                    'cidade' => $checa_cep['cidade'],
                    'uf' => $checa_cep['uf'],
                    'numero' => $request['numero'],
                    'complemento' => $request['complemento'],
                    'destinatario' => $request['destinatario']
                );

                $retorno = array(
                    'status' => 200,
                    'logradouro' => $_SESSION['endereco']['logradouro'],
                    'bairro' => $_SESSION['endereco']['bairro'],
                    'cidade' => $_SESSION['endereco']['cidade'],
                    'uf' => $_SESSION['endereco']['uf'],
                    'numero' => $_SESSION['endereco']['numero'],
                    'complemento' => $_SESSION['endereco']['complemento'],
                    'destinatario' => $_SESSION['endereco']['destinatario']
                );


            } else {

                $retorno = array(
                    'status' => 502
                );

            }

        } else {
            $retorno = array(
                'status' => 501
            );
        }

        return $retorno;

    }

    public function finalizar_cartao(Request $request){

    	$rede = null;

    	$validator = Validator::make(
            [
                'nomeCartao' => $request['nomeCartao'],
                'cpfTitular' => $request['cpfTitular'],
                'numeroCartao' => $request['numeroCartao'],
                'codCartao' => $request['codCartao'],
                'mes' => $request['mes'],
                'ano' => $request['ano'],
                'parcela' => $request['parcela']
            ],
            [
                'nomeCartao' => 'required',
                'cpfTitular' => 'required',
                'numeroCartao' => 'required',
                'codCartao' => 'required',
                'mes' => 'required',
                'ano' => 'required',
                'parcela' => 'required'
            ]
        );

        if (count($validator->errors()->all()) == 0) {

        	// verifica se está logado

        	if (Auth::check() == false) {
        		$status = 502;
        	} else if(empty($_SESSION['produtos']['items'])){
        		$status = 503;
        	} else if($_SESSION['produtos']['checkout'] == false){
        		$status = 504;
        	} else if($_SESSION['produtos']['frete']['check'] == false){
        		$status = 505;
        	} else {

        		// aqui entra a rede

        		$rede = $this->criar_pedido($request);

        		$status = 200;
        	}

        } else {
        	$status = 501;
        }

        return array('status' => $status, 'gateway' => $rede);

    }

    private function criar_pedido($request){

    	DB::beginTransaction();

    	try {

    		$codigo_rede = uniqid();

    		$data = array(
                'cliente_id' => Auth::user()->id,
                'codigo_rede' => $codigo_rede,
                'valor' => $_SESSION['produtos']['total_double'],
                'nome' => Auth::user()->nome_completo,
                'email' => Auth::user()->email,
                'telefone' => Auth::user()->telefone,
                'cpf' => Auth::user()->cpf,
                'cep' => $_SESSION['endereco']['cep'],
                'endereco' => $_SESSION['endereco']['logradouro'],
                'numero' => $_SESSION['endereco']['numero'],
                'complemento' => $_SESSION['endereco']['complemento'],
                'status' => 0,
                'bairro' => $_SESSION['endereco']['bairro'],
                'cidade' => $_SESSION['endereco']['cidade'],
                'estado' => $_SESSION['endereco']['uf'],
                'created_at' => date('Y-m-d h:i:s'),
                'updated_at' => date('Y-m-d h:i:s')
            );

            DB::table('pedidos')->insert($data);
            
            $produtos = $_SESSION['produtos']['items'];


            $ultimo = DB::table('pedidos')->select('id')->orderBy('id', 'DESC')->first();

            $pedido_id = $ultimo->id;

            foreach ($produtos as $key => $value) {

            	$data_produto = array(
            		'pedido_id' => $pedido_id,
            		'produto_id' => $value['id'],
            		'quantidade' => $value['quantidade'],
            		'tamanho' => $value['tamanho']
            	);

            	DB::table('pedidos_produtos')->insert($data_produto);

            	unset($data_produto);

            }

            $retorno_rede = $this->envia_rede($request, $codigo_rede);

            if ($retorno_rede == 58) {
            	DB::rollback();

            	$retorno = false;

            } else if($retorno_rede == 26){
            	DB::rollback();

            	$retorno = false;

            } else if($retorno_rede == 200){
            	
            	DB::commit();

            	$retorno = true;

            } else {
            	DB::rollback();

            	$retorno = false;
            }
    		
    	} catch (Exception $e) {

    		$retorno_rede = 500;
    		
    		DB::rollback();

    		$retorno = false;

    	}

    	if ($retorno == true) {
    		$this->envia_email_cliente();

            session_destroy(); // checkout

    	}

    	return $retorno_rede;

    }

    // gateway de pagamento

    private function envia_rede($request, $codigo_rede){

    	/*$api_testes = 'https://api-hom.userede.com.br/erede/v1/transactions';
        $api_producao = 'https://api.userede.com.br/erede/v1/transactions';

    	$postdata = array(
    		'kind' => 'credit',
    		'reference' => $codigo_rede,
    		'amount' => 500,
    		'installments' => 2,
    		'cardHolderName' => $request['nomeCartao'],
    		'cardNumber' => $request['numeroCartao'],
    		'expirationMonth' => $request['mes'],
    		'expirationYear' => $request['ano'],
    		'securityCode' => $request['codCartao'],
    		'subscription' => false // pagamento recorrente
    	);

    	$ch = curl_init($api_testes);
    	curl_setopt_array($ch, array(
    		CURLOPT_POST => TRUE,
    		CURLOPT_RETURNTRANSFER => TRUE,
    		CURLOPT_HTTPHEADER => array(
                'Authorization: Basic '. base64_encode("50079557:4913bb24a0284954be72c4258e229b86"),
    			'Content-Type: application/json'
    		),
    		CURLOPT_POSTFIELDS => json_encode($postdata)
    	));

    	$response = curl_exec($ch);


    	if($response === FALSE){
    		die(curl_error($ch));
    	}

    	$responseData = json_decode($response, TRUE);

    	//return $responseData['returnCode']; temporário api n aceita ambiente local*/

    	return 200;

    }

    private function envia_email_cliente(){

    }


    // rede pay n aceita

    public function finalizar_boleto(){

    }

    public function teste_rede(){
    	
        $api_testes = 'https://api-hom.userede.com.br/erede/v1/transactions';
        $api_producao = 'https://api.userede.com.br/erede/v1/transactions';

    	$postdata = array(
    		'kind' => 'credit',
    		'reference' => 545454,
    		'amount' => 5000,
    		'installments' => 2,
    		'cardHolderName' => 'Anderson Santana Coelho',
    		'cardNumber' => 4716662102990854,
    		'expirationMonth' => 4,
    		'expirationYear' => 2018,
    		'securityCode' => 609,
    		'softDescriptor' => 'Coelho',
    		'subscription' => false // pagamento recorrente
    	);

    	$ch = curl_init($api_testes);
    	curl_setopt_array($ch, array(
    		CURLOPT_POST => TRUE,
    		CURLOPT_RETURNTRANSFER => TRUE,
    		CURLOPT_HTTPHEADER => array(
                'Authorization: Basic '. base64_encode("50079557:4913bb24a0284954be72c4258e229b86"),
    			'Content-Type: application/json'
    		),
    		CURLOPT_POSTFIELDS => json_encode($postdata)
    	));

    	$response = curl_exec($ch);


    	if($response === FALSE){
    		die(curl_error($ch));
    	}

    	$responseData = json_decode($response, TRUE);

    	var_dump($responseData);

    }

}
