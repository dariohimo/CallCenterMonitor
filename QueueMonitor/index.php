<?php
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
