<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class VestidosController extends Controller{

    public function index() {
    	$array = $this->get_vestidos(8, 'all', 'all');

        return View::make('categorias.vestidos')->with(array( 'itens' => $array ));
    }
    

    public function vestidos_json(Request $request) {

    	$array = $this->get_vestidos($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    }

    public function get_vestidos($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_vestidos/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
