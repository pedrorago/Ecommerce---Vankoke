<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class BlusasController extends Controller{

    public function index() {
    	$array = $this->get_blusas(8, 'all', 'all');

        return View::make('categorias.blusas')->with(array( 'itens' => $array ));
    }
    

    public function blusas_json(Request $request) {

    	$array = $this->get_blusas($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    }

    public function get_blusas($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_blusas/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
