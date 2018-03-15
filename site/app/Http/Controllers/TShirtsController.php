<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class TShirtsController extends Controller{

    public function index() {
    	$array = $this->get_tshirts(8, 'all', 'all');

        return View::make('categorias.t_shirts')->with(array( 'itens' => $array ));
    }
    

    public function tshirts_json(Request $request) {

    	$array = $this->get_tshirts($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    }

    public function get_tshirts($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_tshirts/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
