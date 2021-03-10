<?php
//header('Content-Type: application/json');

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

