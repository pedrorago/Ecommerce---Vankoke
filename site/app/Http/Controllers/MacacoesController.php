<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class MacacoesController extends Controller{

    public function index() {
    	$array = $this->get_macacoes(8, 'all', 'all');

        return View::make('categorias.macacoes')->with(array( 'itens' => $array ));
    }
    

    public function macacoes_json(Request $request) {

    	$array = $this->get_macacoes($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    }

    public function get_macacoes($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_macacoes/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
