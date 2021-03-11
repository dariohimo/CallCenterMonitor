<?php
//hacer un array solo de llamadas
//echo $ruta . "\n";
$data = $datos;
$total_llamadas = array();
$total_agentes = array();
foreach($data as $keycola){
	$total_llamadas = array_merge($total_llamadas, $keycola['llamadas']);
	$total_agentes = array_merge($total_agentes, $keycola['agentes']);
}
/* echo "Datos:\n";
echo "<br>Llamadas:<br>";
print_r($total_llamadas);
echo "<br>Agentes<br>";
print_r($total_agentes); */

?>

<div class="container">
<div class="row">
<div class="col-8">
	<div class="container">
		<h2>Estado de colas con llamadas</h2>		
		<table class="table table-hover table-sm">
			<thead>
			<tr class="table-active">
			<th scope="col">Cola</th>
			<th scope="col">Agentes</th>
			<th scope="col">Atendiendo</th>
			<th scope="col">En Cola</th>
			<th scope="col">Completadas</th>
			<th scope="col">Abandonadas</th>		
			<th scope="col">SL</th>
			</tr>
		</thead>
		<tbody>
<?php
foreach($data as $datos){
	if($datos['llamactuales'] || $datos['llamencola']){
?>
			<tr class="table-default">
			  <th scope="row"><?php echo $datos['nomcola']; ?></th>
			  <td><?php echo sizeof($datos['agentes']); ?></td>
			  <td><?php echo $datos['llamactuales']; ?></td>
			  <td><?php echo $datos['llamencola']; ?></td>			  
			  <td><?php echo $datos['llamcontestadas']; ?></td>
			  <td><?php echo $datos['llamabandonadas']; ?></td>
			  <td><?php echo str_replace('within', 'en', $datos['niveldeservicio']); ?></td>
			</tr>
<?php
	}
}
?>
		</tbody>
		</table>	
	</div>
	<div class="container">		
			<h2>Operadores en llamada</h2>		
		<div class="row">
<?php
$tipo_etiqueta = array("Pausado" => "danger", "Ocupado" => "secondary", "Conectado" => "primary", "Offline" => "light", "Libre" => "success", "Timbrando" => "warning");
foreach($total_agentes as $agente) {
	//solo se muestera los agentes con llamada
	if(array_key_exists('callerid', $agente) && $agente['estado'] == "Conectado"){
	//print_r($agente);
?>
			<div class="col-sm-2" align="center">
				<!--div align="center" class="alert alert-<?php echo $tipo_etiqueta[$agente['estado']];?>"-->
					<span class="badge badge-<?php echo $tipo_etiqueta[$agente['estado']];?>"><?php echo $agente['canal'];?> <?php echo $agente['estado'];?>
					<br><?php echo $agente['nombre'];?>
					<br>Num: <?php echo $agente['callerid'];?>
					<br>Tiempo: <?php echo $agente['duracion'];?>									
					</span>
				<!--/div-->
			</div>
<?php
}
}
?>		
		</div>
	</div>
</div>
<div class="col-4">
	<div class="container">
	<h2>Llamadas entrantes</h2>
		<table class="table table-hover table-sm">
			<thead>
			<tr class="table-active">
			<th scope="col">Número</th>
			<th scope="col">Canal</th>
			<th scope="col">Tiempo</th>
			</tr>
		</thead>
		<tbody>
<?php
//$tipo_fila = array('table-light','table-secondary');
foreach($total_llamadas as $llamada){	
?>
			<tr class="table-default">			  
			  <td><?php echo $llamada['callerid']; ?></td>
			  <td><?php echo $llamada['canal']; ?></td>
			  <td><?php echo $llamada['tespera']; ?></td>			  
			</tr>
<?php
//array_push($tipo_fila, array_shift($tipo_fila));
}
?>		
		</tbody>
		</table>	
	</div>	
</div>
</div>
</div>
