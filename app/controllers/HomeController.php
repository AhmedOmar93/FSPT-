<?php

class HomeController extends BaseController {
	public function home(){
		return View::make('master');
	}

	public function test(){
		return View::make('home');
	}
}
