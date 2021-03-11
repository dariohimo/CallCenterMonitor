<?php namespace Models;

Use Memcached;

class Cola{
	private $mc;

	public function __construct(){
		$this->mc = new Memcached();
		$this->mc->addServer('localhost', 11211) or die ("Unable to connect");
	}
	//leer infocolas o infocanales
	public function mcread($info){
		$datos = ( $this->mc->get($info) ) ? $this->mc->get($info) : array();
		if(!empty($datos)){
			return $datos;
		}else{
			return $datos = array("error" => "error");
		}
	}
}
?>