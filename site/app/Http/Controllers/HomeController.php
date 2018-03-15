<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\View;

class HomeController extends Controller {

	public function home(){
		return view("home");
	}
 
}
