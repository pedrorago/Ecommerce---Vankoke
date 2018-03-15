<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LookBookController extends Controller {

	public function index(){
		return view('lookbook');
	}

	public function colecao(Request $request){

		$ano = $request['ano'];

		if (isset($ano)) {
			if (!empty($ano)) {

				$itens = json_decode(file_get_contents($_SESSION['admin_path'].'/json/colecoes/'.$ano),true);

				$status = 200;

			} else {
				$status = 500;
			}

		} else {
			$status = 500;
		}
		
		return array('status' => $status, 'itens' => $itens);

	}
 
}
