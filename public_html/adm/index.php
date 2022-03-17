<?php 
session_start();
//API - https://tools.keycdn.com/geo.json?host=179.228.220.77

//https://www.google.com.br/maps/place/R.+Pref.+M%C3%A1rio+Leite,+345+-+S%C3%A3o+Francisco,+S%C3%A3o+Sebasti%C3%A3o+-+SP,+11629-257/@-23.7577109,-45.4178618,17z/data=!3m1!4b1!4m5!3m4!1s0x94d29c81a283d7fd:0xae8f693b8cf19d16!8m2!3d-23.7577109!4d-45.4156731
if($_SESSION['admin']==""){
	unset ($_SESSION['sess']);
	unset ($_SESSION['id_adm']);
	unset ($_SESSION['admin']);
	unset ($_SESSION['nivel']);
	unset ($_SESSION['loja']);
    header('location:'.$host.'/adm/autentica.php');
}else{

	$charset="UTF-8";

	$url=$_GET['url'];
	//$url=$_SERVER['REQUEST_URI'];
	list ($var1, $var2, $var3, $var4, $var5, $var5 ) = explode ('/', $url);
	if($url==""){header('location:inicial');}

	if($_REQUEST['acao']=="edit"){
		header('Content-type: text/html; charset=.$charset.');
		header("Expires: on, 01 Jan 1970 00:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		$meta_cache="<meta http-equiv='Cache-Control' content='no-cache, no-store, must-revalidate' />".PHP_EOL;
		$meta_cache.="<meta http-equiv='cache-control' content='max-age=0' />".PHP_EOL;
		$meta_cache.="<meta http-equiv='cache-control' content='no-cache' />".PHP_EOL;
		$meta_cache.="<meta http-equiv='expires' content='0' />".PHP_EOL;
		$meta_cache.="<meta http-equiv='expires' content='Tue, 01 Jan 1970 00:00:00 GMT' />".PHP_EOL;
		$meta_cache.="<meta http-equiv='pragma' content='no-cache' />".PHP_EOL;
	}

	$simbolo_moeda="R$";
	$formato_moeda=".,";
	$masc_moeda="moeda_real";
	$host_adm=$host."/adm";

	include("permissoes_adm.php");

	$diretorio="img_up/"; 
	$data = date('d-m-Y H:i:s');
	$expira=time() + (1 * 3600); //1 hora			
	setcookie ("sess", time(),$expira);

	$base_ck=$host."/img_up/editor/";
	setcookie ("base_ck", $base_ck,$expira); 
	$session_sess=$_SESSION['sess'];
	$session_id_adm=$_SESSION['id_adm'];
	$session_admin=$_SESSION['admin'];
	$session_nivel=$_SESSION['nivel'];
	$session_loja=$_SESSION['loja'];

	$id=$_REQUEST['id'];
	if ($id==""){$id=1;}

	$pag = ($_GET['pag']);
	$pag = filter_var($pag, FILTER_VALIDATE_INT);

	$inicio = 0;$limite = 15;
	if ($pag!=''){$inicio = ($pag - 1) * $limite;}else{$pag=1;}

	$pg_int="S";
	$conf_alt_p=90;

	$pg = $var2;
	if ($pg==""){$pg="institucional";}

	$idioma = $_REQUEST['idioma'];
	if ($idioma==""){$idioma="1";}

	$acao=$_REQUEST['acao'];

	$sessao=$_REQUEST['sessao'];

	if ($var2=="sair" or $pg=="sair"){
		//grava_log('logoff',$session_cli,'---','');	
		//session_destroy();
		unset ($_SESSION['sess']);
		unset ($_SESSION['id_adm']);
		unset ($_SESSION['admin']);
		unset ($_SESSION['nivel']);
		unset ($_SESSION['loja']);
		$expira=time()-1;
		setcookie ("base_ck","",$expira);
		header('Location:'.$host);	
	}else{

		if ($meta_tit==""){$meta_tit=$nome_site;}
?>
		<!DOCTYPE html>
		<!--[if IE 8]>			<html class="ie ie8"> <![endif]-->
		<!--[if IE 9]>			<html class="ie ie9"> <![endif]-->
		<!--[if gt IE 9]><!-->	<html> <!--<![endif]-->
		<html>
			<head>
				<title><?php echo $meta_tit;?></title>
				<?php echo $meta_cache;?>
				<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
				<meta name="Author" content="Portal Hosting Soluções Web">
				<meta http-equiv="X-UA-Compatible" content="IE=edge">
				<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
				<link rel="shortcut icon" href="<?php echo $host;?>/img_site/favicon.ico">

				<link rel="stylesheet" href="<?php echo $host;?>/adm/css/bootstrap_adm.min.css" media="all" type="text/css" />

				<link rel="stylesheet" href="<?php echo $host;?>/css/font-awesome.css" media="all" type="text/css" />
				<link rel="stylesheet" href="<?php echo $host;?>/css/animate.css" rel="stylesheet">

				<link rel="stylesheet" href="<?php echo $host;?>/adm/css/ui.css" media="all" type="text/css" />
				<link rel="stylesheet" href="<?php echo $host;?>/adm/css/jquery.autocomplete.css" media="all" type="text/css" />
				<link rel="stylesheet" href="<?php echo $host;?>/adm/css/datepicker.css" media="all" type="text/css" />
				<!-- <link rel="stylesheet" href="<?php echo $host;?>/adm/js/scriptaculous/lists.css" type="text/css"/>
				<script type="text/javascript" src="<?php echo $host;?>/adm/js/scriptaculous/prototype.js"></script>
				<script type="text/javascript" src="<?php echo $host;?>/adm/js/scriptaculous/scriptaculous.js"></script>
				<script type="text/javascript" language="JavaScript">
					function populateHiddenVars() {
						document.getElementById('imageFloatOrder').value = Sortable.serialize('imageFloatContainer');
					return true;
					}
				</script> -->
				<link rel="stylesheet" href="<?php echo $host;?>/adm/estilo.css" media="all" type="text/css" />

			</head>
			<?php 
			if ($pg=="cliente"){$onload=" onLoad='document.formulario.nome.focus()'";}
			if ($pg=="acessorios" || $pg=="estaleiros" || $pg=="motor" || $pg=="regioes" || $pg=="tipos_embarcacao"){$onload=" onLoad='document.formulario.topico.focus()'";}
			if ($pg=="barcos"){$onload=" onLoad='document.formulario.tipo.focus()'";}
			if ($pg=="cliente"){$onload=" onLoad='document.formulario.nome.focus()'";}

			echo "<body".$onload.">";
			?>

			<header class="navbar navbar-default">
			<!-- <header class="navbar navbar-inverse bs-docs-nav" role="banner"> -->
			  <div class="container">
				<div class="navbar-header">
				  <button class="navbar-toggle" type="button" data-toggle="collapse" data-target=".bs-navbar-collapse">
					<span class="sr-only">Menu</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				  </button>
				  <a href="<?php echo $host;?>/adm/" class="navbar-brand"><img src="<?php echo $host;?>/img_site/logo_adm.png" style='margin-left:15px;height:80px' border="0" alt="Home"></a></a>
				</div>

				<nav class="collapse navbar-collapse bs-navbar-collapse" role="navigation">
				
				<div class="espaco_sup text-right"><!-- Olá<?php echo "Id=".$session_id_adm." Nome=".$session_admin." Nível=".$session_nivel;?> --></div>

				  <ul class="nav navbar-nav navbar-right text-center">
					<li>
					  <a href="<?php echo $host_adm;?>/institucional" title="Institucional" disabled><i class="icone fa fa-sitemap"></i></span><br>INSTITUCIONAL</a>
					</li>
					<li>
					  <a href="<?php echo $host_adm;?>/newsletter" title="Newsletter"><i class="icone fa fa-envelope"></i></span><br>NEWSLETTER</a>
					</li>
					

					<li class="dropdown">
						<a href="#" title="Configurações" class="dropdown-toggle" data-toggle="dropdown"><i class="icone fa fa-cog"></i></span><br>CONFIGURAÇÕES&nbsp;<b class="caret"></b></a>
						<ul class="dropdown-menu navbar-left text-left">
							<?php
							If ($session_nivel <= 3){
								echo "<li><a href='".$host_adm."/config'>Dados gerais</a></li>";
								echo "<li><a href='".$host_adm."/usuarios'>Usuários</a></li>";
								//echo "<li><a href='".$host_adm."/logados'>Logados</a></li>";
							}Else{
								echo "<li><a href='".$host_adm."/usuarios'>Alterar senha</a></li>";
							}
							
							?>
						  <li class="divider"></li>
						  <li><a href="<?php echo $host_adm;?>/sair">Sair</a></li>
						</ul>
					  </li>
				  </ul>
				</nav>

			  </div>
			</header>
			
			<div class="cont_site">
				<div class="clear2"></div>
				<!-- Início conteúdo -->
				<div class="container">
					<?php

					switch ($pg){

						
						Case "config": 
							include("config_adm.php");
						break;

						case "inicial":
							include("inicial_adm.php");
						break;

						case "institucional":
							include("institucional_adm.php");
						break;

						case "newsletter":
							include("newsletter_adm.php");
						break;

						Case "usuarios": 
							include("usuarios_adm.php");
						break;


						Default:
						echo "<br><br><br><br><p class='text-center'>Ops...<br><br><br><br>";

					}

					$ua = date('Y-m-d H:i:s');
					
					$up = $conn->prepare('update admin set ult_acesso=:ua,logado=:logado where id=:id');
					$up -> bindParam(':ua',$ua,PDO::PARAM_STR);
					$up -> bindValue(':logado','S',PDO::PARAM_STR);
					$up -> bindParam(':id',$id,PDO::PARAM_INT);
					$up -> execute();
				
					?>

					<br><br>
				</div>
			</div>
			<!-- Fim conteúdo -->

			<div id="footer">
				<div class="container"><p class="text-right"><b><a href='http://www.portalhosting.com.br' targe='_blank'>Portal Hosting - Desenvolvimento e Soluções WEB</a></b></p><br><br><br></div>
			</div>

			<script type="text/JavaScript" src="<?php echo $host;?>/adm/js/jquery-1.8.3.min.js"></script>
			<script type="text/javascript" src="<?php echo $host;?>/adm/js/bootstrap_adm.min.js"></script>
			<script type="text/javascript" src="<?php echo $host;?>/adm/js/form_valida.js"></script>
					
			<script type="text/JavaScript" src="<?php echo $host;?>/adm/js/jquery-ui-1.9.2.custom.min.js"></script>
			<script type="text/JavaScript" src="<?php echo $host;?>/adm/js/jquery.autocomplete.js"></script>
			<script type="text/javascript" src="<?php echo $host;?>/adm/js/func.js"></script>

			<script type="text/javascript" src="<?php echo $host;?>/adm/editor/ckeditor.js"></script>
			<script type="text/javascript" src="<?php echo $host;?>/adm/editor/adapters/jquery.js"></script>

			<script type='text/javascript'>
				var host = "<?php echo $host;?>";
				$(document).ready(function(){<?php echo $script;?>});
			</script>
			<script type="text/javascript" src="<?php echo $host;?>/adm/scripts_adm.js"></script>
			
			<?php if ($pg=="logados"){?>
			<script>
			$(document).ready(function(){
				setInterval(function(){
				$("#usuarios").load('<?php echo $host;?>/adm/ver_logados.php')
				}, 5000);
			});
			</script>
			<?php }?>


			<?php if ($pg=="conteudo"){?><script>document.getElementById("topico").focus();</script><?php }?>

		</body>
	</html>

	<?php
	}//sair
	
} // login
?>