<?php namespace Config;
class Enrutador{
  public static function run(Request $request){
    $controlador = $request->getControlador() . "Controller";
    $ruta = ROOT . "Controllers" . DS . $controlador . ".php";
    //print $ruta;
    $metodo = $request->getMetodo();
    if($metodo == "index.php"){
      $metodo = "index";
    }
    $argumento = $request->getArgumento();
    if(is_readable($ruta)){
      require_once $ruta;
      $mostrar = "Controllers\\" . $controlador;
      $controlador = new $mostrar;
      if(!isset($argumento)){
        $datos = call_user_func(array($controlador, $metodo));
      }else{
        $datos = call_user_func_array(array($controlador, $metodo), $argumento);
      }
    }
    //Cargar Vista
    $ruta = ROOT . "Views" . DS . $request->getControlador() . DS . $request->getMetodo() . ".php";
	require_once "Views/template.php";
    if(is_readable($ruta) && !array_key_exists('error',$datos)){
      require_once $ruta;
    }else{
      print "No se encontró la ruta: $ruta";
    }
  }
}
?>
