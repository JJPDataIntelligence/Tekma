<?php
if ($pg_int <> "S"){
	$redir="Location:index.php";
	header($redir);
}

$link_pg=$host_adm."/".$var2;

$acao=$var3;
//$id=$var4;


if ($acao == "" or $acao=="alt_ok"){ $acao="vazio"; }



switch ($acao){
	
	case "vazio":

		$sql = "select * from config where id = :id";
		$sql = $conn->prepare($sql);
		$sql->bindValue(':id', '1', PDO::PARAM_INT);
		$sql->execute();

		if($sql->rowCount()==0){
			echo "Nenhum arquivo encontrado";
		}else{

			$rs = $sql->fetch(PDO::FETCH_ASSOC);
	
			$tipo_sis=$rs['tipo'];
			$nome=$rs['nome'];$nome=str_replace("´","'",$nome);
			$cpf_cnpj=$rs['cpf_cnpj'];
			$endereco=$rs['endereco'];$endereco=str_replace("´","'",$endereco);
			$bairro=$rs['bairro'];$bairro=str_replace("´","'",$bairro);
			$cidade=$rs['cidade'];$cidade=str_replace("´","'",$cidade);
			$uf=$rs['uf'];
			$cep=$rs['cep'];
			$email=$rs['email'];
			$telefone=$rs['telefone'];
			$facebook=$rs['facebook'];
			$twitter=$rs['twitter'];
			$g_plus=$rs['g_plus'];
			$youtube=$rs['youtube'];
			$instagram=$rs['instagram'];
			$whats=$rs['whats'];
			$tripadvisor=$rs['tripadvisor'];
			$pinterest=$rs['pinterest'];
			$atendimento=$rs['atendimento'];
			$nro_deptos=$rs['nro_deptos'];
			$latitude=$rs['latitude'];
			$longitude=$rs['longitude'];
			$zoom=$rs['zoom'];
			$ea=$rs['ea'];
			$sa=$rs['sa'];
			$sv=$rs['sv'];
			$url_site=$rs['url_site'];
			$tempo_sessao=$rs['tempo_sessao'];

			$title=$rs['title'];
			$description=$rs['description'];
			$keywords=$rs['keywords'];
			$tag_head=$rs['tag_head'];
			$tag_script=$rs['tag_script'];
			
			
			echo "<div class='panel panel-default'>";
			echo "	<div class='panel-heading bg text-right text-uppercase' style='font-size:18px;'>";
			echo "		<a href='?pg=".$pg."'><strong>Dados gerais</strong></a>&nbsp;";
			echo "	</div>";

			if ($var3=="alt_ok") {
				echo "<br><div class='alert alert-success alert-dismissible' role='alert'>";
				echo "<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
				echo "<p class='text-center'><b>Dados alterados.</b></p>";
				echo "</div>";
			}

			include("form_config_adm.php");


			echo "</div>";
		}		

	break;


	case "alterar":

		//$id=$_POST['id'];
		$id=1;
		$nome=$_POST['nome'];$nome=str_replace("'","´",$nome);
		$cpf_cnpj=$_POST['cpf_cnpj'];
		$endereco=$_POST['endereco'];$endereco=str_replace("'","´",$endereco);
		$bairro=$_POST['bairro'];$bairro=str_replace("'","´",$bairro);
		$cidade=$_POST['cidade'];$cidade=str_replace("'","´",$cidade);
		$uf=$_POST['uf'];
		$cep=$_POST['cep'];
		$email=$_POST['email'];
		$telefone=$_POST['telefone'];
		$facebook=$_POST['facebook'];
		$twitter=$_POST['twitter'];
		$youtube=$_POST['youtube'];
		$g_plus=$_POST['g_plus'];
		$instagram=$_POST['instagram'];
		$whats=$_POST['whats'];
		$tripadvisor=$_POST['tripadvisor'];
		$pinterest=$_POST['pinterest'];
		$atendimento=$_POST['atendimento'];
		$nro_deptos=$_POST['nro_deptos'];
		$latitude=$_POST['latitude'];
		$longitude=$_POST['longitude'];
		$zoom=$_POST['zoom'];
		$ea=$_POST['ea'];
		$sa=$_POST['sa'];
		$sv=$_POST['sv'];
		$url_site=$_POST['url_site'];
		$tempo_sessao=$_POST['tempo_sessao'];
		
		$title=$_POST['title'];
		$description=$_POST['description'];
		$keywords=$_POST['keywords'];
		$tag_head=$_POST['tag_head'];
		$tag_script=$_POST['tag_script'];

		$sql = "select * from config where id = :id";
		$sql = $conn->prepare($sql);
		$sql->bindValue(':id', '1', PDO::PARAM_INT);
		$sql->execute();

		if($sql->rowCount()==0){
			echo "<div class='panel panel-default'>";
			echo "<div class='panel-heading bg text-right' style='font-size:18px;'><a href='?pg=".$pg."'><strong>ERRO</strong></a>&nbsp;";
			echo "</div>";
			echo "<p class='text-center'>REGISTRO NÃO ENCONTRADO</p>";
			echo "<p class='text-right'><a href='javascript:history.back(1)' class='texto'><button type='button' class='btn btn-default'>Voltar</button></a></p>";
		}else{

			if ($nro_deptos<>""){//atualiza nro de deptos
				try {
					$sql = "update config SET nome=:nome,cpf_cnpj=:cpf_cnpj,endereco=:endereco,bairro=:bairro,cidade=:cidade,uf=:uf,cep=:cep,email=:email,telefone=:telefone,facebook=:facebook,instagram=:instagram,whats=:whats,tripadvisor=:tripadvisor,pinterest=:pinterest,twitter=:twitter,youtube=:youtube,g_plus=:g_plus,atendimento=:atendimento,nro_deptos=:nro_deptos,latitude=:latitude,longitude=:longitude,zoom=:zoom,ea=:ea,sa=:sa,sv=:sv,url_site=:url_site,tempo_sessao=:tempo_sessao,title=:title,description=:description,keywords=:keywords,tag_head=:tag_head,tag_script=:tag_script where id=:id";

					$up = $conn->prepare($sql);

					$up -> bindParam(':nome',$nome,PDO::PARAM_STR);
					$up -> bindParam(':cpf_cnpj',$cpf_cnpj,PDO::PARAM_STR);
					$up -> bindParam(':endereco',$endereco,PDO::PARAM_STR);
					$up -> bindParam(':bairro',$bairro,PDO::PARAM_STR);
					$up -> bindParam(':cidade',$cidade,PDO::PARAM_STR);
					$up -> bindParam(':uf',$uf,PDO::PARAM_STR);
					$up -> bindParam(':cep',$cep,PDO::PARAM_STR);
					$up -> bindParam(':email',$email,PDO::PARAM_STR);
					$up -> bindParam(':telefone',$telefone,PDO::PARAM_STR);
					$up -> bindParam(':facebook',$facebook,PDO::PARAM_STR);
					$up -> bindParam(':instagram',$instagram,PDO::PARAM_STR);
					$up -> bindParam(':whats',$whats,PDO::PARAM_STR);
					$up -> bindParam(':tripadvisor',$tripadvisor,PDO::PARAM_STR);
					$up -> bindParam(':pinterest',$pinterest,PDO::PARAM_STR);
					$up -> bindParam(':twitter',$twitter,PDO::PARAM_STR);
					$up -> bindParam(':youtube',$youtube,PDO::PARAM_STR);
					$up -> bindParam(':g_plus',$g_plus,PDO::PARAM_STR);
					$up -> bindParam(':atendimento',$atendimento,PDO::PARAM_STR);
					$up -> bindParam(':nro_deptos',$nro_deptos,PDO::PARAM_STR);
					$up -> bindParam(':latitude',$latitude,PDO::PARAM_STR);
					$up -> bindParam(':longitude',$longitude,PDO::PARAM_STR);
					$up -> bindParam(':zoom',$zoom,PDO::PARAM_STR);
					$up -> bindParam(':ea',$ea,PDO::PARAM_STR);
					$up -> bindParam(':sa',$sa,PDO::PARAM_STR);
					$up -> bindParam(':sv',$sv,PDO::PARAM_STR);
					$up -> bindParam(':url_site',$url_site,PDO::PARAM_STR);
					$up -> bindParam(':tempo_sessao',$tempo_sessao,PDO::PARAM_STR);
					$up -> bindParam(':title',$title,PDO::PARAM_STR);
					$up -> bindParam(':description',$description,PDO::PARAM_STR);
					$up -> bindParam(':keywords',$keywords,PDO::PARAM_STR);
					$up -> bindParam(':tag_head',$tag_head,PDO::PARAM_STR);
					$up -> bindParam(':tag_script',$tag_script,PDO::PARAM_STR);
					$up -> bindParam(':id',$id,PDO::PARAM_INT);
					$up -> execute();

				}catch(PDOException $e) {
				  echo 'Erro: '.$e->getMessage();
				}
				
			}else{//não atualiza nro de deptos
					
					$sql = "update config SET nome=:nome,cpf_cnpj=:cpf_cnpj,endereco=:endereco,bairro=:bairro,cidade=:cidade,uf=:uf,cep=:cep,email=:email,telefone=:telefone,facebook=:facebook,instagram=:instagram,whats=:whats,tripadvisor=:tripadvisor,pinterest=:pinterest,twitter=:twitter,youtube=:youtube,g_plus=:g_plus,atendimento=:atendimento,latitude=:latitude,longitude=:longitude,zoom=:zoom,ea=:ea,sa=:sa,sv=:sv,url_site=:url_site,tempo_sessao=:tempo_sessao,title=:title,description=:description,keywords=:keywords,tag_head=:tag_head,tag_script=:tag_script where id=:id";

					$up = $conn->prepare($sql);

					$up -> bindParam(':nome',$nome,PDO::PARAM_STR);
					$up -> bindParam(':cpf_cnpj',$cpf_cnpj,PDO::PARAM_STR);
					$up -> bindParam(':endereco',$endereco,PDO::PARAM_STR);
					$up -> bindParam(':bairro',$bairro,PDO::PARAM_STR);
					$up -> bindParam(':cidade',$cidade,PDO::PARAM_STR);
					$up -> bindParam(':uf',$uf,PDO::PARAM_STR);
					$up -> bindParam(':cep',$cep,PDO::PARAM_STR);
					$up -> bindParam(':email',$email,PDO::PARAM_STR);
					$up -> bindParam(':telefone',$telefone,PDO::PARAM_STR);
					$up -> bindParam(':facebook',$facebook,PDO::PARAM_STR);
					$up -> bindParam(':instagram',$instagram,PDO::PARAM_STR);
					$up -> bindParam(':whats',$whats,PDO::PARAM_STR);
					$up -> bindParam(':tripadvisor',$tripadvisor,PDO::PARAM_STR);
					$up -> bindParam(':pinterest',$pinterest,PDO::PARAM_STR);
					$up -> bindParam(':twitter',$twitter,PDO::PARAM_STR);
					$up -> bindParam(':youtube',$youtube,PDO::PARAM_STR);
					$up -> bindParam(':g_plus',$g_plus,PDO::PARAM_STR);
					$up -> bindParam(':atendimento',$atendimento,PDO::PARAM_STR);
					$up -> bindParam(':latitude',$latitude,PDO::PARAM_STR);
					$up -> bindParam(':longitude',$longitude,PDO::PARAM_STR);
					$up -> bindParam(':zoom',$zoom,PDO::PARAM_STR);
					$up -> bindParam(':ea',$ea,PDO::PARAM_STR);
					$up -> bindParam(':sa',$sa,PDO::PARAM_STR);
					$up -> bindParam(':sv',$sv,PDO::PARAM_STR);
					$up -> bindParam(':url_site',$url_site,PDO::PARAM_STR);
					$up -> bindParam(':tempo_sessao',$tempo_sessao,PDO::PARAM_STR);
					$up -> bindParam(':title',$title,PDO::PARAM_STR);
					$up -> bindParam(':description',$description,PDO::PARAM_STR);
					$up -> bindParam(':keywords',$keywords,PDO::PARAM_STR);
					$up -> bindParam(':tag_head',$tag_head,PDO::PARAM_STR);
					$up -> bindParam(':tag_script',$tag_script,PDO::PARAM_STR);
					$up -> bindParam(':id',$id,PDO::PARAM_INT);
					$up -> execute();
			}

			$link_pg=$host_adm."/".$var2;

			$url=$link_pg."/alt_ok";

			?><script>window.location.href = "<?php echo $url;?>"</script><?php 
				
		}
		
	break;




}//fecha switch
			
echo "<div class='panel-body'>";
echo "</div><!-- panel-body -->";
echo "</div><!-- panel panel-default -->";
?>
