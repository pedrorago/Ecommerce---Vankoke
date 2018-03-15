<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class SaiasController extends Controller{

    public function index() {
    	$array = $this->get_saias(8, 'all', 'all');

        return View::make('categorias.saias')->with(array( 'itens' => $array ));
    }
    

    public function saias_json(Request $request) {

    	$array = $this->get_saias($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    }

    public function get_saias($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_saias/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
