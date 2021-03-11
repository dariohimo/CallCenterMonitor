<?php namespace Models;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
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