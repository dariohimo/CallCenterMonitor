<?php namespace Models;

Use Memcached;

class Cola{
	private $mc;

	public function __construct(){
		$this->mc = new Memcached();
		$this->mc->addServer('localhost', 11211) or die ("Unable to connect");
	}
	//leer infocolas o infocanales
	public function mcread($tipo){
		$datos = $this->mc->get($tipo);
		return $datos;
	}
	//preparar los datos de la cola
}
?>