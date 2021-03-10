<?php namespace Views;

Use Controllers\colasController as ListaColas;
$template = new Template();

class Template{
	public function __construct(){	  
	$lista = new ListaColas();
	$listado = $lista->listacolas();
	$protocol = ( $_SERVER['HTTPS'] == 'on' ) ? 'https://':'http://';
	//$directorio = explode('/', $_SERVER['REQUEST_URI']);
	$url = $protocol . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']; //returns the current URL	
	
	$page = $_SERVER['PHP_SELF'];
	$sec = "3";
	header("Refresh: $sec; url=$url");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Issabel | Monitor ACD </title>
    <link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/bootstrap.min.css" media="screen">
    <!-- <link rel="stylesheet" href="<?php echo URL; ?>Views/template/css/bootstrap.min.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <link rel="stylesheet" href="../4/litera/bootstrap.css" media="screen"> -->
    <!-- <link rel="stylesheet" href="../_assets/css/custom.min.css"> -->
  <!-- <script id="_carbonads_projs" type="text/javascript" src="https://srv.carbonads.net/ads/CKYIE23N.json?segment=placement:bootswatchcom&amp;callback=_carbonads_go"></script> -->
</head>
<body>
  <div class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="container">
        <a href="<?php echo URL;?>" class="navbar-brand">Monitor de colas</a>
        <!--button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button-->        
          <ul class="navbar-nav">
			<div class="container">
				<a class="btn btn-outline-secondary" href="<?php echo URL;?>" role="button">TODAS</a>			  
<?php
foreach($listado as $cola) {
?>
				<a class="btn btn-outline-secondary" href="<?php echo URL . 'colas/ver/' . $cola;?>" role="button"><?php echo $cola?></a>
				<!--div class="dropdown-divider"></div-->
<?php
}
?>
			</div>
          </ul>

          <!--ul class="nav navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="#" target="_blank">Link Externo</a>
            </li>
          </ul-->
        
      </div>
    </div>
<div class="container">
<?php
  }
  public function __destruct(){
?>
<footer id="footer">
  <div class="row">
    <div class="col-lg-12">
      <p>Página de pruebas</p>
    </div>
  </div>
</footer>

<!--footer class="footer">
      <div class="container">
        <span class="text-muted">Place sticky footer content here.</span>
      </div>
</footer-->

<!--footer id="footer">
        <div class="row">
          <div class="col-lg-12">
            <ul class="list-unstyled">
              <li class="float-lg-right"><a href="#top">Back to top</a></li>
              <li><a href="https://blog.bootswatch.com" onclick="pageTracker._link(this.href); return false;">Blog</a></li>
              <li><a href="https://blog.bootswatch.com/rss/">RSS</a></li>
              <li><a href="https://twitter.com/bootswatch">Twitter</a></li>
              <li><a href="https://github.com/thomaspark/bootswatch/">GitHub</a></li>
              <li><a href="../help/#api">API</a></li>
              <li><a href="../help/#donate">Donate</a></li>
            </ul>
            <p>Made by <a href="https://thomaspark.co">Thomas Park</a>.</p>
            <p>Code released under the <a href="https://github.com/thomaspark/bootswatch/blob/master/LICENSE">MIT License</a>.</p>
            <p>Based on <a href="https://getbootstrap.com" rel="nofollow">Bootstrap</a>. Icons from <a href="https://fontawesome.com/" rel="nofollow">Font Awesome</a>. Web fonts from <a href="https://fonts.google.com/" rel="nofollow">Google</a>.</p>

          </div>
        </div>
</footer-->
</div>
<script src="https://bootswatch.com/_vendor/jquery/dist/jquery.min.js"></script>
<script src="https://bootswatch.com/_vendor/popper.js/dist/umd/popper.min.js"></script>
<script src="https://bootswatch.com/_vendor/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="https://bootswatch.com/_assets/js/custom.js"></script>
</body>
</html>
<?php
  }
}
?>
