<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaginasController extends Controller {

	public function institucional(){
		return view('institucional');
	}

	public function contato(){
		return view('contato');
	}
 
}
