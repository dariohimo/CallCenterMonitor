<?php namespace Controllers;

use Models\Cola as Cola;

class colasController{
	private $colas;
	private $canales;
	private $lista;
	
	public function __construct(){
		$this->colas = new Cola();
		$this->canales = new Cola();
	}

	public function index(){
		$infocola = $this->colas->mcread('infocolas');	
		return $infocola;
	}

	public function ver(){
		$uri = explode("/",  $_SERVER['REQUEST_URI']);
		$id = end($uri);
		$infocola = $this->colas->mcread('infocolas');		
		$cola = $infocola[$id];		
		return $cola;
	}
	
	public function listacolas(){
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);
		return $this->lista;
	}
	
/* 	public static function run(){
	$infocola = $this->colas->mcread('infocolas');
	$this->lista = array_keys($infocola);
	return $this->lista;
} */
	
}

?>