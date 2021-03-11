<?php
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__FILE__)) . DS);
define('ASTV', '13');
$protocol = ( $_SERVER['HTTPS'] == 'on' ) ? 'https://':'http://';
$directorio = explode('/', $_SERVER['REQUEST_URI']);
$url = $protocol . $_SERVER['SERVER_NAME'] . DS . $directorio[1] . DS; //returns the current URL
//define('URL', "http://localhost/proyecto/");
define('URL', $url);

require_once "Config/Autoload.php";
Config\Autoload::run();
//require_once "Views/template.php";
Config\Enrutador::run(new Config\Request());
//Controllers\colaController::run();
//require_once "Controllers/infocolas.php";
?>
