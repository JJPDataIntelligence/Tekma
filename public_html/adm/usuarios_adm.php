<?php
if ($pg_int <> "S"){
	$redir="Location:index.php";
	header($redir);
}

$top_ref="usuarios";

$acao=$var3;
$id=$var4;

if($acao=="" or $acao=="msg"){$acao="vazio";}

$link_pg=$host_adm."/".$var2;


$msn=$var5;

If ($msn=="cad_ok"){
	$aviso="<div class='alert alert-success alert-dismissible' role='alert'>";
	$aviso.="<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
	$aviso.="<p class='text-center'>Cadastro efetuado com sucesso.</p>";
	$aviso.="</div>";
} 
		
If ($msn=="alt_ok"){
	$aviso="<div class='alert alert-success alert-dismissible text-center' role='alert'>";
	$aviso.="<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
	$aviso.="<p class='text-center'>Alteração efetuada com sucesso.</p>";
	$aviso.="</div>";
}

If ($msn=="alt_erro"){
	$aviso="<div class='alert alert-danger alert-dismissible text-center' role='alert'>";
	$aviso.="<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
	$aviso.="<p class='text-center'>Não foi possível encontrar o registro.</p>";
	$aviso.="</div>";
}

If ($msn=="del_ok"){
	$aviso="<div class='alert alert-success alert-dismissible' role='alert'>";
	$aviso.="<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
	$aviso.="<p class='text-center'>Registro apagado com sucesso.</p>";
	$aviso.="</div>";
} 


echo "<div class='panel panel-default'>";
echo "<div class='panel-heading bg text-right topico'><a href='".$link_pg."'><strong>Usuários</strong></a>";

If ($permissao_cad=="S"){
	echo "&nbsp;&nbsp;<a href='".$link_pg."/cadastrar' title='Cadastrar novo usuário'><span class='glyphicon glyphicon-plus text-right'></span></a>";
}else{
	if ($acao<>"altera"){$acao="edit";}
	//$id=$session_id_adm;
}
echo "</div><!-- panel-heading -->";

if ($acao<>"vazio"){
	echo "<div class='panel-body'>";
}

//echo "Acao=".$acao." ID:".$id;
switch ($acao){
	
	Case "vazio":
		If ($aviso<>""){echo $aviso;}
		?>
		<!-- <div class="rows bg">
			<div class="col-sm-12">
				<form method=post action='?pg=cliente&acao=edit' name="form_cli" id="form_cli" class="navbar-form" role="search">
					<div class="input-group">
						<input type="text" class="form-control" placeholder="Cliente" name="b_cliente" id="b_cliente" required><input type="hidden" name="id_cli" id="id_cli">
						<div class="input-group-btn">
							<button class="btn btn-default" type="submit"><i class="glyphicon glyphicon-search"></i></button>
						</div>
					</div>
				</form>
			</div>
		<div class='clear'></div>
		</div> -->
		<?php
		echo "<table class='table table-bordered table-hover table-striped'><tbody>";
		
		//$sql="select admin.id,admin.nome,admin.usual,admin.nivel,tipos.topico as regiao, admin.email,admin.tel,admin.cel,admin.site,admin.ult_acesso from admin left outer join tipos on admin.regiao = tipos.id where (admin.nivel > :session_nivel or admin.id = :session_id_adm) and admin.status <> :status order by admin.nivel,admin.nome";
		$sql="select * from admin where (nivel > :session_nivel or id = :session_id_adm) and status <> :status order by nivel,nome";
		$sql = $conn->prepare($sql);
		$sql->bindParam(':session_nivel', $session_nivel, PDO::PARAM_STR);
		$sql->bindParam(':session_id_adm', $session_id_adm, PDO::PARAM_STR);
		$sql->bindValue(':status', 'D', PDO::PARAM_INT);
		$sql->execute();

		if($sql->rowCount()==0){
			echo "<tr>";
			echo "<td>";
			echo "<p class='text-center'><br><br><b>Nenhum registro encontrado<br><br><a href='javascript:history.back(1)'>Voltar</p>";
			echo "</td>";
			echo "</tr>";
		}else{

			echo "<tr>";
			echo "<td><b>Nome</b></td>";
			//echo "<td><b>Grupo</b></td>";
			//echo "<td><b>Região</b></td>";
			echo "<td><b>Email</b></td>";
			echo "<td><b>Telefones</b></td>";
			echo "<td><b>Último acesso</b></td>";
			echo "<td width='10%' colspan=2'><b>Funções</b></td>";
			echo "</tr>";

			while ($rs = $sql->fetch(PDO::FETCH_ASSOC)) {

				$id=$rs['id'];
				$login=$rs['login'];
				$login=str_replace("´","'",$login);
				/*
				$senha=$rs['senha'];
				$senha=str_replace("´","'",$senha);
				*/
				$nome=$rs['nome'];
				$nome=str_replace("´","'",$nome);
				$usual=$rs['usual'];
				$usual=str_replace("´","'",$usual);
				$email=$rs['email'];
				$tel=$rs['tel'];
				$tel=str_replace("´","'",$tel);
				$cel=$rs['cel'];
				$cel=str_replace("´","'",$cel);
				$nivel=$rs['nivel'];
				$permissoes=$rs['permissoes'];
				$regiao=$rs['regiao'];
				$site=$rs['site'];
				$site=str_replace("´","'",$site);
				$ult_acesso=$rs['ult_acesso'];

				$status=$rs['status'];
				
				if ($tel==""){
					$telefones=$cel;
				}else{
					if ($cel<>""){ 
						$telefones=$tel. " / " .$cel;
					}else{
						$telefones=$tel;
					}
				}

				if ($ult_acesso<>"0000-00-00 00:00:00"){
					$ult_acesso=date('d/m/Y H:i:s',strtotime($ult_acesso));
				}
				 
				
				If ($nivel=="1"){$n_nivel="Administrador";}
				If ($nivel=="2"){$n_nivel="Diretor";}
				If ($nivel=="3"){$n_nivel="Gerente";}
				If ($nivel=="4"){$n_nivel="Vendedor";}
				If ($nivel=="5"){$n_nivel="Visitante";}

				$status=$rs['status'];

				$grupo=$n_nivel;

				echo "<tr>";
				echo "<td align=left>".$nome."</td>";
				//echo "<td>".$grupo."</td>";
				//echo "<td>".$regiao."</td>";
				echo "<td>".$email."</td>";
				echo "<td>".$telefones."</td>";
				echo "<td>".$ult_acesso."</td>";
				echo "<td width='3%' align=center><a href='".$link_pg."/edit/".$id,"' title='Alterar'><span class='glyphicon glyphicon-pencil text-right'></span></a></td>";
				echo "<td width='3%' align=center><a href='".$link_pg."/del/".$id."' title='Apagar'><span class='glyphicon glyphicon-trash text-right'></span></a></td>";
				echo "</tr>";

			}
			echo "<tr><td colspan=9></td></tr></tbody></table>";
		}
			
	break;

	Case "cadastrar":
		
	$nome="";
		$action=$link_pg."/salva";
		$bot="salva";
		$escreve="Novo Usuário";
		$volta=$link_pg;
		$status="A";
		include("form_usuarios_adm.php");
	
	break;


	Case "salva":

		$id=$_POST['id'];
		$login=$_POST['login'];
		$login=str_replace("'","´",$login);
		$senha=$_POST['senha'];
		if ($senha<>""){$senha=sha1($senha);}
		//$senha=str_replace("'","´",$senha);
		$nome=$_POST['nome'];
		$nome=str_replace("'","´",$nome);
		$usual=$_POST['usual'];
		$usual=str_replace("'","´",$usual);
		$email=$_POST['email'];
		$tel=$_POST['tel'];
		$tel=str_replace("'","´",$tel);
		$cel=$_POST['cel'];
		$tel=str_replace("'","´",$tel);
		$nivel=$_POST['nivel'];
		$permissoes=$_POST['permissoes'];
		$regiao=$_POST['regiao'];
		$site=$_POST['site'];
		$site=str_replace("'","´",$site);
		$ult_acesso="0000-00-00";
		$logado='N';
		$status=$_POST['status'];
		
		$sql = "select * from admin where login = :login";
		$sql = $conn->prepare($sql);
		$sql->bindParam(':login', $login, PDO::PARAM_STR);
		$sql->execute();

		if($sql->rowCount()==0){
			
			
			$sql = 'insert into admin (login,senha,nome,usual,email,tel,cel,nivel,permissoes,regiao,site,ult_acesso,logado,status) values(:login,:senha,:nome,:usual,:email,:tel,:cel,:nivel,:permissoes,:regiao,:site,:ult_acesso,:logado,:status)';
			$ins = $conn->prepare($sql);
			$ins -> bindParam(':login',$login,PDO::PARAM_STR);
			$ins -> bindParam(':senha',$senha,PDO::PARAM_STR);
			$ins -> bindParam(':nome',$nome,PDO::PARAM_STR);
			$ins -> bindParam(':usual',$usual,PDO::PARAM_STR);
			$ins -> bindParam(':email',$email,PDO::PARAM_STR);
			$ins -> bindParam(':tel',$tel,PDO::PARAM_STR);
			$ins -> bindParam(':cel',$cel,PDO::PARAM_STR);
			$ins -> bindParam(':nivel',$nivel,PDO::PARAM_STR);
			$ins -> bindParam(':permissoes',$permissoes,PDO::PARAM_STR);
			$ins -> bindParam(':regiao',$regiao,PDO::PARAM_STR);
			$ins -> bindParam(':site',$site,PDO::PARAM_STR);
			$ins -> bindParam(':ult_acesso',$ult_acesso,PDO::PARAM_STR);
			$ins -> bindParam(':logado',$logado,PDO::PARAM_STR);
			$ins -> bindParam(':status',$status,PDO::PARAM_STR);
			$ins -> execute();

			$sql = "select * from admin where login = :login";
			$sql = $conn->prepare($sql);
			$sql->bindParam(':login', $login, PDO::PARAM_STR);
			$sql->execute();

			$rs = $sql->fetch(PDO::FETCH_ASSOC);
			$id=$rs['id'];
			$id_ref=$id;

			$log="insert into admin (login,senha,nome,usual,email,tel,cel,nivel,permissoes,regiao,site,ult_acesso,logado,status) values ('".$login."','".$senha."','".$nome."','".$usual."','".$email."','".$tel."','".$cel."','".$nivel."','".$permissoes."','".$regiao."','".$site."','".$ult_acesso."','N','".$status."')";
			if ($grava_log=="S"){grava_log('insert',$id,'admin',$log);}

			//include("salva_up.php");
			
			$url=$link_pg."/edit/".$id."/cad_ok";
			?><script>window.location.href = "<?php echo $url;?>"</script><?php 
			
		}else{
			echo "<p class='bg-danger text-center'>Já existe um usuário com este nome.<br><a href=javascript:history.back(-1)>Voltar</a></p>";
		}

	break;

	Case "edit":

		If ($aviso<>""){echo $aviso;}

		$sql = "select * from admin where id = :id";
		$sql = $conn->prepare($sql);
		$sql->bindParam(':id', $id, PDO::PARAM_INT);
		$sql->execute();

		if($sql->rowCount()==0){
			echo "<br><br>Não foi possível recuperar o registro<br><br>";
		}else{

			$rs = $sql->fetch(PDO::FETCH_ASSOC);

			$id=$rs['id'];
			$login=$rs['login'];
			$login=str_replace("´","'",$login);
			//$senha=$rs['senha'];
			//if ($senha<>""){$senha=base64_encode($senha);}
			//$senha=str_replace("´","'",$senha);
			$nome=$rs['nome'];
			$nome=str_replace("´","'",$nome);
			$usual=$rs['usual'];
			$usual=str_replace("´","'",$usual);
			$email=$rs['email'];
			$tel=$rs['tel'];
			$tel=str_replace("´","'",$tel);
			$cel=$rs['cel'];
			$cel=str_replace("´","'",$cel);
			$nivel=$rs['nivel'];
			$permissoes=$rs['permissoes'];
			$regiao=$rs['regiao'];
			$site=$rs['site'];
			$site=str_replace("´","'",$site);
			$ult_acesso=$rs['ult_acesso'];
			$status=$rs['status'];

			$log="";
			if ($grava_log=="S"){grava_log('editou',$id,'admin',$log);}

			$escreve="Alterar";
			$action=$link_pg."/altera";
			

			$sql =" select * from imagens where id_ref=:id and (extensao='gif' or extensao='jpeg' or extensao='jpg' or extensao='png') and top_ref=:top_ref";
			$sql = $conn->prepare($sql);
			$sql->bindParam(':id', $id, PDO::PARAM_INT);
			$sql->bindParam(':top_ref', $top_ref, PDO::PARAM_INT);
			$sql->execute();
								
			if($sql->rowCount()==0){
				$rs = $sql->fetch(PDO::FETCH_ASSOC);
				$img_id=$rs['id'];
				$img_usuario=$rs['img'];
				$imagem="S";
			}

			$volta=$link_pg;//link do botão volta do form

			include("form_usuarios_adm.php");
			echo "</div>";
			echo "</div>";


		}
	
	break;

	Case "altera":

		$id=$_POST['id'];
		$id_ref=$id;
		$img_id=$_POST['img_id'];
		$login=$_POST['login'];
		$login=str_replace("'","´",$login);
		$senha=$_POST['senha'];
		if (trim($senha)<>""){
			$senha=sha1($senha);
			//$senha=str_replace("'","´",$senha);
			$alt_senha="senha=:senha,";
		}else{
			$alt_senha="";
		}
		$nome=$_POST['nome'];
		$nome=str_replace("'","´",$nome);
		$usual=$_POST['usual'];
		$usual=str_replace("'","´",$usual);
		$email=$_POST['email'];
		$tel=$_POST['tel'];
		$tel=str_replace("'","´",$tel);
		$cel=$_POST['cel'];
		$tel=str_replace("'","´",$tel);
		$nivel=$_POST['nivel'];
		$permissoes=$_POST['permissoes'];
		$regiao=$_POST['regiao'];
		$site=$_POST['site'];
		$site=str_replace("'","´",$site);
		$status=$_POST['status'];

		
		If ($permissao_alt=="S"){$up_adm=",permissoes=:permissoes,nivel=:nivel";}else{$up_adm="";}

		$sql="update admin set ".$alt_senha."nome=:nome,usual=:usual,email=:email,tel=:tel,cel=:cel,regiao=:regiao,site=:site,status=:status".$up_adm." where id=:id";
		$up = $conn->prepare($sql);
		if (trim($senha)<>""){
			$up -> bindParam(':senha',$senha,PDO::PARAM_STR);
		}
		$up -> bindParam(':nome',$nome,PDO::PARAM_STR);
		$up -> bindParam(':usual',$usual,PDO::PARAM_STR);
		$up -> bindParam(':email',$email,PDO::PARAM_STR);
		$up -> bindParam(':tel',$tel,PDO::PARAM_STR);
		$up -> bindParam(':cel',$cel,PDO::PARAM_STR);
		$up -> bindParam(':regiao',$regiao,PDO::PARAM_STR);
		$up -> bindParam(':site',$site,PDO::PARAM_STR);
		$up -> bindParam(':status',$status,PDO::PARAM_STR);
		If ($permissao_alt=="S"){
			$up -> bindParam(':permissoes',$permissoes,PDO::PARAM_STR);
			$up -> bindParam(':nivel',$nivel,PDO::PARAM_STR);
		}
		$up -> bindParam(':id',$id,PDO::PARAM_INT);
		$up -> execute();

		$log="update admin set ".$alt_senha."nome=".$nome.",usual=".$usual.",email=".$email.",tel=".$tel.",cel=".$cel.",regiao=".$regiao.",site=".$site.",status=".$status.$up_adm." where id=".$id;
		if ($grava_log=="S"){grava_log('update',$id,'admin',$log);}

		if ($img_id<>""){
			$acao="altera_img";
			$id=$img_id;
			
		}
		
		//include("salva_up.php");

		//echo $img_sql;

		$url=$link_pg."/edit/".$id."/alt_ok";
		
		?><script>window.location.href = "<?php echo $url;?>"</script><?php 
		

	break;

	case "del":

		//$id=$_REQUEST['id'];
		$confirma=$_REQUEST['confirma'];
		
		if ($confirma != "sim") {

			$sql = "select * from admin where id = :id";
			$sql = $conn->prepare($sql);
			$sql->bindParam(':id', $id, PDO::PARAM_INT);
			$sql->execute();

			if($sql->rowCount()==0){
				echo "<div class='panel-heading bg text-right topico'>";
				echo "	<a href='$link_pg'><strong>ERRO</strong></a>&nbsp;";
				echo "</div>";
				echo "<p class='text-center'>REGISTRO NÃO ENCONTRADO</p>";
				echo "<p class='text-right'><a href='javascript:history.back(1)' class='texto'><button type='button' class='btn btn-default'>Voltar</button></a></p>";
			}else{

				$rs = $sql->fetch(PDO::FETCH_ASSOC);

				$nome=$rs['nome'];
				$nome=str_replace("´", "'",$nome);
				

				echo "<div class='panel-heading bg text-right text-uppercase topico'>";
				echo "	<strong>Excluir</strong>&nbsp;";
				echo "</div>";
				?>

				<form action="<?php echo $link_pg;?>/del" method="post">
				<input type="hidden" name="id" value="<?php echo $id;?>">
				<input type="hidden" name="idioma" value="<?php echo $idioma;?>">
				<input type="hidden" name="confirma" value="sim">
				<?php 
				$mensagem="<br><br>";
				
				echo "<div class='col-sm-12 bg-danger'><p class='text-center danger'><br><br>Favor confirmar a exclusão de<br><b>".$nome."</b><br><br></p></div>";
				echo "<div class='form-group'>";
				echo "	<div class='col-sm-12'><p class='text-right'><a href='javascript:history.back(-1)'><button type='button' class='btn btn-default'>Voltar</button></a>&nbsp;&nbsp;&nbsp;<button type='submit' name='submit' id='submit' class='btn btn-default'><b>Enviar</b></button></p></div>";
				echo "</div>";
			}

		}else{

			$id=$_POST['id'];

			$sql = "select * from admin where id = :id";
			$sql = $conn->prepare($sql);
			$sql->bindParam(':id', $id, PDO::PARAM_INT);
			$sql->execute();

			if($sql->rowCount()==0){
				echo "<div class='panel panel-default'>";
				echo "<div class='panel-heading bg text-right topico'>";
				echo "	<a href='$link_pg'><strong>ERRO</strong></a>&nbsp;";
				echo "</div>";
				echo "<p class='text-center'>REGISTRO NÃO ENCONTRADO</p>";
				echo "<p class='text-right'><a href='javascript:history.back(1)' class='texto'><button type='button' class='btn btn-default'>Voltar</button></a></p>";
			}else{
				
				$rs = $sql->fetch(PDO::FETCH_ASSOC);
				
				$id=$rs['id'];
				/*
				$sql = "delete from admin where id=:id";
				$del = $conn->prepare($sql);
				$del -> bindParam(':id',$id,PDO::PARAM_INT);
				$del -> execute();
				*/
				$senha="@#@#@#@#@#@#@#@#@";
				$status="D";
				$sql='update admin set senha=:senha,status=:status where id=:id';
				$up = $conn->prepare($sql);
				$up -> bindParam(':senha',$senha,PDO::PARAM_STR);
				$up -> bindParam(':status',$status,PDO::PARAM_STR);
				$up -> bindParam(':id',$id,PDO::PARAM_INT);
				$up -> execute();
/*
				$sql = "select * from imagens where id_ref=:id and top_ref=:top_ref";
				$sql = $conn->prepare($sql);
				$sql->bindParam(':id', $id, PDO::PARAM_INT);
				$sql->bindParam(':top_ref', $top_ref, PDO::PARAM_STR);
				$sql->execute();

				while ($rs = $sql->fetch(PDO::FETCH_ASSOC)) {
					
					$img_db=$rs['img'];
					
					if ($img_db<>""){
						$arquivo=$diretorio."/".$img_db;		
						if( file_exists( $arquivo ) ){unlink( $arquivo );}
					}
				}

				$sql = "delete from imagens where id_ref=:id and top_ref=:top_ref";
				$del = $conn->prepare($sql);
				$del->bindParam(':id', $id, PDO::PARAM_INT);
				$del->bindParam(':top_ref', $top_ref, PDO::PARAM_STR);
				$del -> execute();
*/
				
			}

			$url=$link_pg."/msg/".$id."/del_ok";
			?><script>window.location.href = "<?php echo $url;?>"</script><?php 
		}

	break;

}

echo "</div><!-- panel-body -->";
echo "</div><!-- panel panel-default -->";