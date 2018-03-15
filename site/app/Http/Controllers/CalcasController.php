<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class CalcasController extends Controller{

    public function index() {
    	$array = $this->get_calcas(8, 'all', 'all');

        return View::make('categorias.calcas')->with(array( 'itens' => $array ));
    }
    

    public function calcas_json(Request $request) {

    	$array = $this->get_calcas($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    }

    public function get_calcas($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_calcas/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
