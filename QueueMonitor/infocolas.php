<?php
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
$mc = new Memcached();
$mc->addServer('localhost', 11211) or die ("Unable to connect");

$colas = $mc->get('infocolas');
$canales = $mc->get('infocanales');

if(isset($argv[1]))
{
	print_r($colas[$argv[1]]);
	//echo json_encode($colas['$argv[1]']);	
}
else
{
	print_r($colas);
	//echo json_encode($colas);
}
echo "\n";
print_r($canales);

?>

