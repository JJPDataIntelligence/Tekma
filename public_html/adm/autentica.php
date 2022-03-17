<?php
$charset="utf-8";
//$charset="ISO-8859-1";
header('Content-type: text/html; charset=.$charset.');
session_start();
include("../conn.php");
$conn=conecta();
include("funcoes.php");

$pg=$_POST['pg'];
$pg_int="S";

if ($pg==""){$pg="vazio";}



$sql = "select * from config where id = :id";
$sql = $conn->prepare($sql);
$sql->bindValue(':id', '1', PDO::PARAM_INT);
$sql->execute();

if($sql->rowCount()==0){
	echo "Nenhum arquivo encontrado";
}else{
	$rs = $sql->fetch(PDO::FETCH_ASSOC);
	$nome_site=$rs['nome'];
	$email_site=$rs['email'];
	$host=$rs['url_site'];
	$host_adm=$host."/adm";
}

switch ($pg)
{	
	case "vazio":
		?>
		<!DOCTYPE html>
		<html lang="pt-br">
		  <head>
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
			<meta name="viewport" content="width=device-width, initial-scale=1">
			<meta name="description" content="">
			<meta name="author" content="">
			<link rel="shortcut icon" href="<?php echo $host;?>/favicon.ico">
			<title><?php echo $nome_site;?></title>
			<link href="<?php echo $host_adm;?>/css/bootstrap.css" rel="stylesheet">
			<link href="<?php echo $host;?>/css/font-awesome.css" rel="stylesheet">
			<script src="<?php echo $host_adm;?>/js/jquery.js"></script>
			<script src="<?php echo $host_adm;?>/js/bootstrap.js"></script>

		  </head>
		  <body>
				<p align='center'><br><br><center>
					<form name="formulario" id="formulario" method="POST" action="<?php echo $host;?>/adm/autentica.php" enctype="multipart/form-data">	
						<input type="hidden" name="pg" value="autentica">
						<div style='width:320px;border-radius:5px;border:1px solid #808080;background-color:#fff;padding-top:15px;'> 

							<div style='padding:5px;' class='center'><img src="../img_site/logo_adm.png" border="0" alt="" class='img-responsive'></div>

							<div class='center'>
							<br><br>
						
							<?php 
							
							if ($_SESSION['erro_sess']=="erro"){
								echo "<div class='bs-example'>";
								echo "<div class='alert alert-danger'>";
								echo "<a href='#' class='close' data-dismiss='alert'>&times;</a>";
								echo "<strong>Usuário ou Senha inválidos</strong>";
								echo "</div>";
								echo "</div>";
								unset ($_SESSION['erro_sess']);
							}
							?>
						
							<div style='padding:15px;'>	
							
								<div class="form-group first">
								<div class="input-group col-sm-12">
									<span class="input-group-addon" style='width:40px'><i class="fa fa-user"></i></span>
									<input type="text" class="form-control" name="usuario" id="usuario"required placeholder="Usuário"/>	
								</div>
							</div>
							
							<div class="form-group last">
								<div class="input-group col-sm-12">
									<span class="input-group-addon" style='width:40px'><i class="fa fa-key"></i></span>
									<input type="password" class="form-control" name="senha" id="senha" required placeholder="Senha"/>
								</div>
							</div>
							
							<button type="submit" class="btn btn-default col-xs-12">Login</button><br>			
							<div class='clear'></div> 	
							
						</div>
					</p><!-- <div class="text-right text-p"><a href="http://www.portalhosting.com.br/help-desk" title="">Portal Hosting Solu��es Web&nbsp;&nbsp;</a></div> -->
				</form>
		  </body>
		</html>
		<?php
	break;
	case "autentica":
		$login = Trim($_POST['usuario']);
		$senha = Trim($_POST['senha']);
		$senha = sha1($senha);
		$loja  = Trim($_POST['loja']);
		if ($loja==""){$loja=0;}
		$login = str_replace("'","´",$login); 
		$log_erro_senha=str_replace("'","´",$_POST['senha']); 

		$log = "select * from admin where login='".$login."' and senha='".$log_erro_senha."' and status='A'";
		
		//$conn=conecta();
		$sql = "select * from admin where login=:login and senha=:senha and status=:status";
		$sql = $conn->prepare($sql);
		$sql->bindValue(':login', $login, PDO::PARAM_STR);
		$sql->bindValue(':senha', $senha, PDO::PARAM_STR);
		$sql->bindValue(':status', 'A', PDO::PARAM_STR);
		$sql->execute();

		if($sql->rowCount()>0){
			
			$rs = $sql->fetch(PDO::FETCH_ASSOC);

			$id=$rs['id'];
			$nome=$rs['nome'];
			$nivel=$rs['nivel'];
			$loja=$rs['loja'];
			$sess=time()."|".$id."|".$nome."|".$nivel;
			$_SESSION['sess'] = $sess;
			$_SESSION['id_adm'] = $id;
			$_SESSION['admin'] = $nome;
			$_SESSION['nivel'] = $nivel;
			$_SESSION['loja'] = $loja;
			$expira=time() + (1 * 3600); //1 hora			
			setcookie ("sess", time(),$expira);
			$ua = date('Y-m-d H:i:s');
			$redir="index.php";
			$redir=$host."/adm/";
			
			if ($grava_log=="S"){grava_log('login',$id,'admin',$login);}
			//atualiza
			
			try {
				$up = $conn->prepare('update admin set ult_acesso=:ua,logado=:logado where id=:id');
				$up -> bindParam(':ua',$ua,PDO::PARAM_STR);
				$up -> bindValue(':logado','S',PDO::PARAM_STR);
				$up -> bindParam(':id',$id,PDO::PARAM_INT);
				$up -> execute();

			}catch(PDOException $e) {
				echo 'Erro: '.$e->getMessage();
			}
		
		}else{
			/*session_destroy();*/
			//Limpa
			unset ($_SESSION['sess']);
			unset ($_SESSION['id_adm']);
			unset ($_SESSION['admin']);
			unset ($_SESSION['nivel']);
			unset ($_SESSION['loja']);
			//Redireciona para a p�gina de autentica��o
			$_SESSION['erro_sess']="erro";
			$redir=$host."/adm/autentica.php";
			if ($grava_log=="S"){grava_log('erro_login',$id,'admin',$log);}
		}
		//echo "Redi=$redir / N�vel=".$nivel." / Sess�o n�vel=".$_SESSION['nivel'];
		header('location:'.$redir);
	break;
}
fecha_conn();
?>