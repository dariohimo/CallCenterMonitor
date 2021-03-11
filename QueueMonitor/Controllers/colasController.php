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
		ksort($infocola);
		return $infocola;
	}

	public function ver(){
		$uri = explode("/",  $_SERVER['REQUEST_URI']);
		$id = end($uri);
		$infocola = $this->colas->mcread('infocolas');
		if(array_key_exists('error',$infocola)){
			return $infocola;
		}else{
			$cola = $infocola[$id];		
			return $cola;
		}
	}
	
	public function listacolas(){
		$infocola = $this->colas->mcread('infocolas');
		$this->lista = array_keys($infocola);			
		asort($this->lista);
		return $this->lista;
	}	
}

?>