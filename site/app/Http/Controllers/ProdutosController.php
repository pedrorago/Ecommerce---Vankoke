<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use View;

class ProdutosController extends Controller
{
    public function index() {

        $array = $this->get_produtos_todos(8, 'all', 'all');

        return View::make('produtos')->with(array( 'itens' => $array ));
    }

    public function produtos_json(Request $request) {

        $array = $this->get_produtos_todos($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

        return $array;
    
    }

    public function mais_vendidos() {

        $array = $this->get_produtos_mais_vendidos(8, 'all', 'all');

        return View::make('categorias.mais_vendidos')->with(array( 'itens' => $array ));
    }

    public function mais_vendidos_json(Request $request) {

        $array = $this->get_produtos_mais_vendidos($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

        return $array;
    
    }
    
    public function outlet() {

    	$array = $this->get_produtos_outlet(8, 'all', 'all');

    	return View::make('outlet')->with(array( 'itens' => $array ));
    
    }

    public function outlet_json(Request $request) {

    	$array = $this->get_produtos_outlet($request['quantidade'], $request['tamanho'], $request['faixa_preco']);

    	return $array;
    
    }
    
    public function produto($codigo, $slug) {

    	$array = $this->get_produto($codigo, $slug);

    	if (!empty($array['produto'])) {

    		return View::make('produto')->with(array( 'produto' => $array['produto'] ));

    	} else {

    		// ou erro 404

            return redirect('/');

    	}

    }

    private function get_produto($codigo, $slug){

        $json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produto_single/'.$codigo.'/'.$slug),true); 
        
        return $json;

    }

    public function get_produtos_todos($qtd, $tamanho, $faixa_preco){

        $json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

        return $json; 

    }

    public function get_produtos_mais_vendidos($qtd, $tamanho, $faixa_preco){

        $json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_mais_vendidos/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

        return $json; 

    }

    public function get_produtos_outlet($qtd, $tamanho, $faixa_preco){

    	$json = json_decode(file_get_contents($_SESSION['admin_path'].'/json/produtos_outlet/'.$qtd.'/'.$tamanho.'/'.$faixa_preco),true);

    	return $json; 

    }

}
