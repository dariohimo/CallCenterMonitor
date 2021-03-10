<?php
require __DIR__ . '/vendor/autoload.php';
$mc = new Memcached();
$mc->addServer('localhost', 11211) or die ("Unable to connect");

fclose(STDIN);
fclose(STDOUT);
fclose(STDERR);
$STDIN = fopen('/dev/null', 'r');
$STDOUT = fopen("/var/log/asterisk/ccmonitor.log", 'wb');
$STDERR = fopen("/var/log/asterisk/ccmonitor-error.log", 'wb');

$queue = "";

while (true) {
/* if(isset($argv[1]))
{
	$queue = $argv[1];
}
else
{
	$queue = "";
} */

$asm = new AGI_AsteriskManager();
//$manager = new Asterisk/AMI/Manager();
if($asm->connect())
{ 
	//echo "Cola: ".$queue;	
	$queues = $asm->Command("queue show $queue");
	$canales = $asm->CoreShowChannels();
	$version = $asm->Command("core show version");
	if(array_key_exists('data', $queues) && array_key_exists('events', $canales) )
	{
		$asm->disconnect();
	}
}
//filtrar la versión
$astver = ( preg_match("/Asterisk ([0-9]+)/", $version['data'], $matches) ) ? $matches[1] : 'error';

//echo "Asterisk Version " . $astver . "\n";
//preparar indices para versión 13+ o 11
$nextension = ($astver >= 13) ? 'Exten':'Extension';
$nchannel = ($astver >= 13) ? 'Channel':'BridgedChannel';
$ncallerid = ($astver >= 13) ? 'CallerIDNum':'CallerIDnum';

//organizar la info de las colas de atención
foreach(explode("\n\n", $queues['data']) as $colas) { 
	$cola['cola'] = strtok($colas, "\n"); //tomar la primera línea
/* 	$cola['split'] = explode(" ", $cola['cola']); //trim(array_shift(explode(" ", $cola['cola'])));
	$cola['nomcola'] = trim($cola['split'][0]);
	$cola['llamencola'] = trim($cola['split'][2]);
	$cola['split'] = explode(",", $cola['cola']);
	$cola['llamcontestadas'] = trim($cola['split'][3]);
	$cola['llamabandonadas'] = trim($cola['split'][4]);
	$cola['niveldeservicio'] = trim($cola['split'][5]);
	unset($cola['split']); */
	
	$cola['nomcola'] = ( preg_match("/^([0-9a-zA-Z]+) /", $cola['cola'], $matches) ) ? $matches[1] : 'error';
	$cola['llamencola'] = ( preg_match("/has ([0-9]+) calls/", $cola['cola'], $matches) ) ? $matches[1] : 'error';
	$cola['llamcontestadas'] = ( preg_match("/C:([0-9]+)/", $cola['cola'], $matches) ) ? $matches[1] : 'error';
	$cola['llamabandonadas'] = ( preg_match("/A:([0-9]+)/", $cola['cola'], $matches) ) ? $matches[1] : 'error';
	$cola['niveldeservicio'] = ( preg_match("/SL:([0-9]+.[0-9]+% within [0-9]+s)/", $cola['cola'], $matches) ) ? $matches[1] : 'error';
		
	$cola['contenido'] = substr($colas, strpos($colas, "\n") + 1);	//tomar el resto del contenido de la cola
	$cola['detalle'] = explode("\n",$cola['contenido']); //dividir cada línea de contenido
	$contador1 = 0;
	$contador2 = 0;
	$cola['agentes'] = array();
	$cola['llamadas'] = array();
	$contador3 = 0;
	foreach($cola['detalle'] as $linea){
		$linea = trim($linea);		
		//separar los agentes y las llamadas	
		if( trim($cola['detalle'][0]) == "Members:" && $linea != "Callers:" && $linea != "No Callers" ){
			if( $linea != "Members:" ){
				$cola['agentes'][$contador1]['linea'] = $linea;
				$cola['agentes'][$contador1]['miembro'] = $cola['nomcola'];
				//encontrar el estado del canal
				if( strpos($linea, "Unavailable") ) {
					$cola['agentes'][$contador1]['estado'] = "Offline";
				}
				elseif( strpos($linea, "paused")) {
					$cola['agentes'][$contador1]['estado'] = "Pausado";
				}				
				elseif( strpos($linea, "Not in use") ) {
					$cola['agentes'][$contador1]['estado'] = "Libre";
				}
				elseif( strpos($linea, "in call") ) {
					$cola['agentes'][$contador1]['estado'] = "Conectado";
					$contador3++;
				}				
				elseif( strpos($linea, "In use") ) {
					$cola['agentes'][$contador1]['estado'] = "Ocupado";
				}
				elseif( strpos($linea, "Ringing")) {
					$cola['agentes'][$contador1]['estado'] = "Timbrando";
				}
				//encontrar las llamadas contestadas
				$cola['agentes'][$contador1]['llamcontestadas'] = ( preg_match("/taken ([0-9]+) calls/", $linea, $matches) ) ? $matches[1]: 0;			
				//crear un array de la información del canal			
				$infoagente = explode("(",$linea);				
				//tomar el nombre del agente y solo la parte del canal Ej: SIP/201, IAX/201
				$cola['agentes'][$contador1]['nombre'] = $infoagente[0];
				$infoagente['canal'] = explode(' ', str_replace(')','',$infoagente[1]));
				$cola['agentes'][$contador1]['canal'] = array_shift($infoagente['canal']);
				
				/*AstV11 Detectar si el nombre del canal está en el valor de BridgedChannel, la duración incluye el tiempo de espera
				el callerid es CallerIDnum y conectado es Channel
				también se podría detectar en Channel, la duración solo es el teimpo de llamada
				el callerid es ConnectedLineNum y conectado es BridgedChannel
				*/				
				foreach( $canales['events'] as $conectados ) {
					if( strpos( $conectados['Channel'], $cola['agentes'][$contador1]['canal']) === 0 && $conectados[$nextension] === $cola['nomcola']){
						$cola['agentes'][$contador1]['callerid'] = $conectados['ConnectedLineNum'];
						$cola['agentes'][$contador1]['duracion'] = $conectados['Duration'];
						if($astver >= 13){
							$indice = array_search($conectados['Linkedid'],array_map(function($element){return $element['Uniqueid'];}, $canales['events']));
							$cola['agentes'][$contador1]['conectado'] = $canales['events'][$indice][$nchannel];
						} else{
							$cola['agentes'][$contador1]['conectado'] = $conectados[$nchannel];
						}					
						$cola['agentes'][$contador1]['cola'] = $conectados[$nextension];
					}
				}
				//}
				//borrar líneas basura
				if( $linea == "Members:" ) { unset($cola['agentes'][$contador1]); }			
				elseif( $linea == "" || $linea == "No Callers" ) { unset($cola['agentes'][$contador1]); } 				
				//tomar solo la parte del canal Ej: SIP/201, IAX/201	
				$contador1++;
			}
		}
		//cambiar el condicional para leer las llamadas
		elseif( $linea == "Callers:" ) {
			$cola['detalle'][0] = "Callers:";
		}
		//indice de llamadas
		elseif( $cola['detalle'][0] = "Callers:" && $linea != "" && $linea != "No Callers" ){			
			$canal = explode(' ', $linea);
			$cola['llamadas'][$contador2]['linea'] = $linea;								
			$cola['llamadas'][$contador2]['canal'] = $canal[1];
			$cola['llamadas'][$contador2]['tespera'] = ( preg_match("/([0-9]+):([0-9]+)/", $linea, $matches) ) ? $matches[0] : '0:00';
			//cotejar el callerid con la información del canal
			$indice = array_search($canal[1],array_map(function($element){return $element['Channel'];}, $canales['events']));
			//en php>5.5 usar la siguiente línea
			//$indice = array_search($canal[1],array_column($canales['events'],'Channel'));
			$cola['llamadas'][$contador2]['callerid'] = $canales['events'][$indice][$ncallerid];
			$cola['llamadas'][$contador2]['cola'] = $canales['events'][$indice][$nextension];
			//borrar líneas basura
			if( $linea == "Callers:" || $linea == "" ) { unset($cola['llamadas'][$contador2]); }
			$contador2++;
		}
	}
	$cola['llamactuales'] = $contador3;
	unset($cola['contenido']);
	unset($cola['detalle']);
	$informacion[$cola['nomcola']] = $cola;
}
/* print_r($informacion);
echo "\n";
print_r($canales['events']); */
$mc->set('infocolas', $informacion, 10) or die ("Unable to save data in the cache");
$mc->set('infocanales', $canales['events'], 10) or die ("Unable to save data in the cache");
sleep (3);
}
?>