<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Auth;
use View;
use Session;
use Correios;

class CarrinhoController extends Controller {

    public function index() {

        $carrinho = $this->carrinho();

        return $carrinho;
        
    }

    private function carrinho(){

    	//session_start(); já foi iniciada na app service provider

        if (!isset($_SESSION['produtos'])) {
            
            $_SESSION['produtos']['items'] = array();
        }

        if (!isset($_SESSION['produtos']['frete'])) {
            
            $_SESSION['produtos']['frete'] = array(
                'check' => false,
                'valor_frete_selecionado' => '0.00',
                'valor_frete_formatado' => 'R$ '.'0,00'
            );

        } else {

            if ($_SESSION['produtos']['frete']['check'] == true) {
                $this->recalcular_frete();
            }

        }

        if (!isset($_SESSION['produtos']['cupom'])) {
            
            $_SESSION['produtos']['cupom'] = array(
                'check' => false,
                'valor' => '0.00',
            );

        }


        //var_dump($_SESSION['produtos']);

        $this->atualiza_total($_SESSION['produtos']['items']);

        $_SESSION['produtos']['quantidade'] = count($_SESSION['produtos']['items']);

    	return $_SESSION['produtos'];

    }

    private function atualiza_total($produtos){

    	$total = 0;
        $total_p = 0;

    	foreach ($produtos as $key => $item){
    		$total_p = $total_p + ( $item['preco_double'] * $item['quantidade'] ) ;
    	}

        if (isset($_SESSION['produtos']['frete'])) {
           $total = $total_p + $_SESSION['produtos']['frete']['valor_frete_selecionado'];
        }

        $_SESSION['produtos']['total_produtos'] = number_format($total_p, 2, ',', ' ');

        $_SESSION['produtos']['total'] = number_format($total, 2, ',', ' ');

        $_SESSION['produtos']['total_double'] = $total;

    }

    public function add_produto (Request $request){

    	//session_start(); já foi iniciada na app service provider

    	$id = $request->id;

    	if (isset($_SESSION['produtos'])) {

    		$session =  Session::get('produtos');

    	} else {

    		$_SESSION['produtos'] = array();

            $_SESSION['produtos']['items'] = array();

            $_SESSION['produtos']['frete'] = array(
                'check' => false,
                'valor_frete_selecionado' => '0.00',
                'valor_frete_formatado' => 'R$ '.'0,00'
            );

    	}

        $json = json_decode(file_get_contents($_SESSION['admin_path'].'/busca_produtos/json/'.$id),true); 

    	if (!empty($json)) {

            $tamanho = $request['tamanho'];

            $col = 'quantidade_'.$tamanho;

            $check = DB::table('produtos_estoque')->where('produto_id', $id)->first();

            if (!empty($check)) {
                
                if ($check->$col > 2) {

                    $json['tamanho'] = $tamanho;

                    if (empty($request['qtd'] or !is_numeric($request['qtd']))) {
                        $json['quantidade'] = 1;
                    } else {
                        $json['quantidade'] = $request['qtd'];
                    }

                    $produtos = $_SESSION['produtos']['items'];

                    if ($this->check_produto($produtos, $id, $tamanho) != true) {

                        $_SESSION['produtos']['items'][] = $json;

                        $this->atualiza_total($_SESSION['produtos']['items']);
                    } 
                    
                    $status = 200;


                } else {
                    $status = 501; // fora de estoque
                }

            } else {
                $status = 500; // erro generico
            }

    	} else {
    		$status = 500; // erro generico
    	}

        return $status;

    }

    public function mudar_quantidade(Request $request){

        //session_start(); já foi iniciada na app service provider

        $produtos = $_SESSION['produtos']['items'];

        foreach ($produtos as $key => $item){

            if ($item['id'] == $request->id){

            	if ($request->qtd <= 0) {
            		$request->qtd = 1;
            	}

                $_SESSION['produtos']['items'][$key]['quantidade'] = $request->qtd;
                
            } 
        }

        $_SESSION['produtos']['quantidade'] = count($_SESSION['produtos']['items']);

        $this->atualiza_total($_SESSION['produtos']['items']);

        return $_SESSION['produtos'];

    }

    public function remove_produto(Request $request){

        //session_start();

        $produtos = $_SESSION['produtos']['items'];

        foreach ($produtos as $key => $item){

            if ($item['id'] == $request->id AND $item['tamanho'] == $request->tamanho){

            	$total = $_SESSION['produtos']['total_double'];

            	$total = $total - ( $item['preco_double'] * $item['quantidade'] );

            	if ($total <= 0) {
            		$total = 0;
            	}

            	$_SESSION['produtos']['total'] = number_format($total, 2, ',', ' ');

                unset($_SESSION['produtos']['items'][$key]);
                
            } 
        }

        // reorganizar array apos remover

        $_SESSION['produtos']['items'] = array_values($_SESSION['produtos']['items']);

        if (empty($_SESSION['produtos']['items'])) {
            session_destroy();
        }

        $_SESSION['produtos']['quantidade'] = count($_SESSION['produtos']['items']);

        return $_SESSION['produtos'];

    }

    public function check_produto($produtos, $id, $tamanho){

    	foreach ($produtos as $key => $item){

    		if ($item['id'] == $id and $item['tamanho'] == $tamanho){

    			$_SESSION['produtos']['items'][$key]['quantidade'] = $item['quantidade'] + 1;

    			$this->atualiza_total($_SESSION['produtos']['items']);

    			return true;
    		} 
    	}

    }

    public function checa_cupom(Request $request){

        if (empty($request['codigo']) or !isset($request['codigo'])) {
            $status = 500;
        } else {

            // fazer script de verificar se cupom é valido ou não.. por enquanto retornar falso

            $status = 501;

        }

        return $status;

    }

    public function finalizar(){

        //session_start();

        if (!isset($_SESSION['produtos'])) {
            $status = 501; 
        } else {
            if ($_SESSION['produtos']['quantidade'] <= 0) {
                $status = 502;
            } else if($_SESSION['produtos']['frete']['check'] == false){
                $status = 503;
            } else {

                $_SESSION['produtos']['checkout'] = true;

                $this->atualiza_total($_SESSION['produtos']['items']);

                $status = 200;
            }
        }

        return $status;

    }

    public function calcular_frete(Request $request){

        // session_start(); já iniciada na app provider

        $cep = $request['cep'];

        $dados_logradouro = Correios::cep($cep);

        if (!empty($dados_logradouro)) {

            $dados_normal = [
                'tipo'              => 'pac', 
                'formato'           => 'caixa',
                'cep_destino'       => $cep, // Obrigatório
                'cep_origem'        => '52050010', // Obrigatorio
                'peso'              => '0.15', // Peso em kilos
                'comprimento'       => '16', // Em centímetros
                'altura'            => '2', // Em centímetros
                'largura'           => '11', // Em centímetros
                'diametro'          => '0', // Em centímetros, no caso de rolo
            ];

            $dados_expresso = [
                'tipo'              => 'sedex', 
                'formato'           => 'caixa',
                'cep_destino'       => $cep, // Obrigatório
                'cep_origem'        => '52050010', // Obrigatorio
                'peso'              => '0.15', // Peso em kilos
                'comprimento'       => '16', // Em centímetros
                'altura'            => '2', // Em centímetros
                'largura'           => '11', // Em centímetros
                'diametro'          => '0', // Em centímetros, no caso de rolo
            ];

            $frete_normal = Correios::frete($dados_normal);

            $frete_expresso = Correios::frete($dados_expresso);

            //echo "<pre>".var_dump($frete_normal)."</pre>";

            //echo "<pre>".var_dump($frete_expresso)."</pre>";

            if ($frete_normal['erro']['codigo'] == 0 and $frete_expresso['erro']['codigo'] == 0) {

                if (isset($_SESSION['produtos'])) {

                    $session =  Session::get('produtos');

                    $_SESSION['produtos']['frete'] = array(
                        'check' => true,
                        'valor_pac' => $frete_normal['valor'],
                        'prazo_pac' => $frete_normal['prazo'],
                        'valor_pac_formatado' => 'R$ '.number_format($frete_normal['valor'], 2, ',', '.'),
                        'valor_sedex' => $frete_expresso['valor'],
                        'prazo_sedex' => $frete_expresso['prazo'],
                        'valor_sedex_formatado' => 'R$ '.number_format($frete_expresso['valor'], 2, ',', '.'),
                        'valor_frete_selecionado' => $frete_normal['valor'],
                        'prazo_frete' => $frete_normal['prazo'],
                        'valor_frete_formatado' => 'R$ '.number_format($frete_normal['valor'], 2, ',', '.'),
                        'tipo_frete_selecionado' => 'normal'
                    );

                    $_SESSION['endereco'] = array(
                        'check' => true,
                        'cep' => $dados_logradouro['cep'],
                        'logradouro' => $dados_logradouro['logradouro'],
                        'bairro' => $dados_logradouro['bairro'],
                        'cidade' => $dados_logradouro['cidade'],
                        'uf' => $dados_logradouro['uf'],
                        'numero' => '',
                        'complemento' => '',
                        'destinatario' => ''
                    );

                    $total = $_SESSION['produtos']['total_produtos'];

                    $total = $total + $frete_normal['valor'];

                    $_SESSION['produtos']['total'] = $total;

                }
                
            }

            // adiciona na sessão os valores dos fretes..

            //session_start();

            $retorno = array(
                'status' => 200,
                'valor_frete_formatado' =>  $_SESSION['produtos']['frete']['valor_frete_formatado'],
                'valor_total_formatado' => 'R$ '.number_format($_SESSION['produtos']['total'], 2, ',', ' ')
            );
            
        } else {

            $retorno = array(
                'status' => 500
            );

        }
        
        return $retorno;

    }

    private function recalcular_frete(){

        $cep = $_SESSION['endereco']['cep'];

        $dados_logradouro = Correios::cep($cep);

        if (!empty($dados_logradouro)) {

            $dados_normal = [
                'tipo'              => 'pac', 
                'formato'           => 'caixa',
                'cep_destino'       => $cep, // Obrigatório
                'cep_origem'        => '52050010', // Obrigatorio
                'peso'              => '0.15', // Peso em kilos
                'comprimento'       => '16', // Em centímetros
                'altura'            => '2', // Em centímetros
                'largura'           => '11', // Em centímetros
                'diametro'          => '0', // Em centímetros, no caso de rolo
            ];

            $dados_expresso = [
                'tipo'              => 'sedex', 
                'formato'           => 'caixa',
                'cep_destino'       => $cep, // Obrigatório
                'cep_origem'        => '52050010', // Obrigatorio
                'peso'              => '0.15', // Peso em kilos
                'comprimento'       => '16', // Em centímetros
                'altura'            => '2', // Em centímetros
                'largura'           => '11', // Em centímetros
                'diametro'          => '0', // Em centímetros, no caso de rolo
            ];

            $frete_normal = Correios::frete($dados_normal);

            $frete_expresso = Correios::frete($dados_expresso);

            //echo "<pre>".var_dump($frete_normal)."</pre>";

            //echo "<pre>".var_dump($frete_expresso)."</pre>";

            if ($frete_normal['erro']['codigo'] == 0 and $frete_expresso['erro']['codigo'] == 0) {

                if (isset($_SESSION['produtos'])) {

                    $session =  Session::get('produtos');

                    $_SESSION['produtos']['frete'] = array(
                        'check' => true,
                        'valor_pac' => $frete_normal['valor'],
                        'prazo_pac' => $frete_normal['prazo'],
                        'valor_pac_formatado' => 'R$ '.number_format($frete_normal['valor'], 2, ',', '.'),
                        'valor_sedex' => $frete_expresso['valor'],
                        'prazo_sedex' => $frete_expresso['prazo'],
                        'valor_sedex_formatado' => 'R$ '.number_format($frete_expresso['valor'], 2, ',', '.'),
                        'valor_frete_selecionado' => $frete_normal['valor'],
                        'prazo_frete' => $frete_normal['prazo'],
                        'valor_frete_formatado' => 'R$ '.number_format($frete_normal['valor'], 2, ',', '.'),
                        'tipo_frete_selecionado' => 'normal'
                    );

                    $_SESSION['endereco'] = array(
                        'check' => true,
                        'cep' => $dados_logradouro['cep'],
                        'logradouro' => $dados_logradouro['logradouro'],
                        'bairro' => $dados_logradouro['bairro'],
                        'cidade' => $dados_logradouro['cidade'],
                        'uf' => $dados_logradouro['uf'],
                        'numero' => '',
                        'complemento' => '',
                        'destinatario' => ''
                    );

                    $total = $_SESSION['produtos']['total_produtos'];

                    $total = $total + $frete_normal['valor'];

                    $_SESSION['produtos']['total'] = $total;

                }
                
            }
        }
    
    }

    public function teste_frete(){

        $cep = '52280613';

        //$endereco = Correios::cep('89062086');

        $dados = [
            'tipo'              => 'sedex', 'pac', 
            // Separar opções por vírgula (,) caso queira consultar mais de um (1) serviço. > Opções: `sedex`, `sedex_a_cobrar`
            'formato'           => 'caixa', // opções: `caixa`, `rolo`, `envelope`
            'cep_destino'       => '52280613', // Obrigatório
            'cep_origem'        => '89062080', // Obrigatorio
            //'empresa'         => '', // Código da empresa junto aos correios, não obrigatório.
            //'senha'           => '', // Senha da empresa junto aos correios, não obrigatório.
            'peso'              => '1', // Peso em kilos
            'comprimento'       => '16', // Em centímetros
            'altura'            => '11', // Em centímetros
            'largura'           => '11', // Em centímetros
            'diametro'          => '0', // Em centímetros, no caso de rolo
            // 'mao_propria'       => '1', // Não obrigatórios
            // 'valor_declarado'   => '1', // Não obrigatórios
            // 'aviso_recebimento' => '1', // Não obrigatórios
        ];

        echo '<pre>'.var_dump(Correios::frete($dados)).'</pre>';
    
    }


}
