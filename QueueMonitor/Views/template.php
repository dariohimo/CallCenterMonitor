<?php namespace Views;
//Este código está bajo la licencia MIT, puedes revisar la licencia el el fichero LICENSE en la raiz del proyecto o en:
//https://github.com/neovoice/CallCenterMonitor/blob/master/LICENSE
Use Controllers\colasController as ListaColas;
if(array_key_exists('error',$datos)){
	$page = $_SERVER['PHP_SELF'];
	$sec = "3";
	$protocol = ( $_SERVER['HTTPS'] == 'on' ) ? 'https://':'http://';	
	$url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; //returns the current URL	
	header("Refresh: $sec; url=$url");
	echo "Error, recargando en 3 segundos...\n";	
} else{
	$template = new Template();
}
class Template{
	public function __construct(){	  
	$lista = new ListaColas();
	$listado = $lista->listacolas();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Asterisk | Monitor ACD </title>
    <link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/bootstrap.min.css" media="screen">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<body onload = "JavaScript:AutoRefresh(3000);">
<!--body-->
  <div class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a href="<?php echo URL;?>" class="navbar-brand">Ver:</a>       
          <ul class="navbar-nav">
			<div class="container" >
				<a class="badge badge-dark" href="<?php echo URL;?>" role="button">TODAS</a>
				<!--a class="btn btn-outline-secondary btn-sm" href="<?php echo URL;?>" role="button">TODAS</a-->
<?php
foreach($listado as $cola) {
?>
				<a class="badge badge-dark" href="<?php echo URL . 'colas/ver/' . $cola;?>" role="button"><?php echo $cola?></a>				
<?php
}
?>
			</div>
          </ul>   
      </div>
    </div>
<div class="container">
<?php
  }
  public function __destruct(){
?>
</div>
<!--div class="navbar fixed-bottom navbar-light bg-light">
<div class="container">
<footer id="footer">
  <div class="row">
    <div class="col-lg-12">
      <p>Desarrollado por: Eliécer Tatés - eliecer.tates@ridetel.com</p>
    </div>
</div>
</div>
</footer>
</div-->
<script src="https://bootswatch.com/_vendor/jquery/dist/jquery.min.js"></script>
<script src="https://bootswatch.com/_vendor/bootstrap/dist/js/bootstrap.min.js"></script>

<script type = "text/JavaScript">
	function AutoRefresh(t) {		
		setTimeout("location.reload(true);", t);
	}
</script>
</body>
</html>
<?php
  }
}
?>
