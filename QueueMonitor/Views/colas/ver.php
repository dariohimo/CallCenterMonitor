<!--Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE -->
<div class="container">
<div class="row">
<div class="col-8">
	<div class="container">
		<h5>Estado de las colas</h5>
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
			<tr class="table-default">
			  <th scope="row"><?php echo $datos['nomcola']; ?></th>
			  <td><?php echo sizeof($datos['agentes']); ?></td>
			  <td><?php echo $datos['llamactuales']; ?></td>
			  <td><?php echo $datos['llamencola']; ?></td>
			  <td><?php echo $datos['llamcontestadas']; ?></td>
			  <td><?php echo $datos['llamabandonadas']; ?></td>
			  <td><?php echo str_replace('within', 'en', $datos['niveldeservicio']); ?></td>
			</tr>
		</tbody>
		</table>	
	</div>
	<div class="container">		
			<h5>Estado de los operadores:&nbsp;<span class="badge badge-success">Libre</span>&nbsp;<span class="badge badge-primary">En llamada</span>&nbsp;<span class="badge badge-secondary">Ocupado</span>&nbsp;<span class="badge badge-danger">Pausado</span>&nbsp;<span class="badge badge-info">No conectado</span></h5>
		<div class="row">
<?php
$tipo_etiqueta = array("Pausado" => "danger", "Ocupado" => "secondary", "Conectado" => "primary", "Offline" => "info", "Libre" => "success", "Timbrando" => "warning");
foreach($datos['agentes'] as $agente) {
	//print_r($agente);
?>
			<div class="col-md-2" align="center">
				<!--div align="center" class="alert alert-<?php echo $tipo_etiqueta[$agente['estado']];?>"-->
					<span class="badge badge-<?php echo $tipo_etiqueta[$agente['estado']];?>"><?php echo str_replace("@from-queue","", $agente['canal']);?>
					<br><?php echo str_replace("@from-queue","",$agente['nombre']);?>
<?php
	if(array_key_exists('callerid', $agente)){
?>					
					<br>Num: <?php echo $agente['callerid'];?>
					<br>Tiempo: <?php echo $agente['duracion'];?>
<?php					
}					
?>					
				</span>
				<!--/div-->
			</div>
<?php
}
?>		
		</div>
	</div>
</div>
<div class="col-4">
	<div class="container">
	<h5>Llamadas entrantes</h5>
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
foreach($datos['llamadas'] as $llamada){
?>
			<tr class="table-default">			  
			  <td><?php echo $llamada['callerid']; ?></td>
			  <td><?php echo array_shift(explode('-',$llamada['canal'])); ?></td>
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
