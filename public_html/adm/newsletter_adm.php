<?php
if ($pg_int <> "S"){
	$redir="Location:index.php";
	header($redir);
}

$link_pg=$host_adm."/".$var2;

$acao=$var3;
//$tipo=$_REQUEST['tipo'];

if ($acao == ""){ $acao="vazio"; }

echo "<div class='panel panel-default'>";
echo "<div class='panel-heading bg text-right' style='font-size:18px;'>";
echo "<a href='?pg=".$pg."'><strong>Contatos</strong></a>&nbsp;";
//echo "&nbsp;<a href='?pg=".$pg."&acao=cadastrar' title='Adicionar contato'><span class='glyphicon glyphicon-plus'></span></a>&nbsp;";
echo "<a href='exporta.php' target='_blank' title='Exportar'><span class='glyphicon glyphicon-save'></span></a>";
echo "</div><!-- panel-heading -->";


switch ($acao){
	
	case "vazio":

		$n_reg="select count(*) as total from newsletter";
		$n_reg = $conn->prepare($n_reg);
		$n_reg->execute();
		$total = $n_reg->fetch(PDO::FETCH_ASSOC);
		$total = $total['total'];

		$prox = $pag + 1;
		$ant = $pag - 1;
		$ultima_pag = ceil($total / $limite);
		$penultima = $ultima_pag - 1;  
		$adjacentes = 2;


		
		$sql="select * from newsletter";
		$sql.="	order by data desc";
		$sql.=" limit ".$inicio.", ".$limite;
		$sql = $conn->prepare($sql);
		$sql->execute();


		
		echo "<table class='table table-bordered table-hover table-striped'><tbody>";

		if($sql->rowCount()==0){
			echo "<tr><td colspan=5><br><p class='alerta text-center'><strong>Nenhum email encontrado</strong></p><br></td></tr>";
			echo "<tr><td colspan=5 class='text-center'><a href='?pg=".$pg."&acao=cadastrar' title='Adicionar novo e-mail'><b>Adicionar novo e-mail</b></a></td></tr>";
		}else{
			echo "<tr>";
			echo "<td><b>Data</b></td>";
			echo "<td><b>E-mail</b></td>";
			//echo "<td><b>Celular</b></td>";
			//echo "<td width='5%' class='text-center'><a href='?pg=".$pg."&acao=cadastrar&lista=".$id."&tpc=upload' title='Importar contatos via arquivo'><span class='glyphicon glyphicon-upload'></span></a></td>";
			//echo "<td width='5%' class='text-center'><a href='?pg=".$pg."&acao=cadastrar_lista' title='Criar nova lista'><span class='glyphicon glyphicon-plus'></span></a></td></tr>";
			echo "</tr>";
			while ($rs = $sql->fetch(PDO::FETCH_ASSOC)) {

				$id=$rs['id'];
				$data=$rs['data'];
				$email=$rs['email'];
				//$celular=$rs['celular'];

				if($data<>""){
					list ($data, $hora) = explode (' ', $data);
					list ($ano, $mes,  $dia) = explode ('-', $data);
					$data = $dia."/".$mes."/".$ano." - ".$hora;
				}else{
					$data="00-00-0000";
				}
				
				echo "<tr>";
				echo "<td align=left>".$data."</a></td>";
				echo "<td>".$email."</td>";
				//echo "<td>".$celular."</td>";
				//echo "<td width='5%' class='text-center'><a href='exporta.php?lista=".$id."' target='_blank' title='Exportar'><span class='glyphicon glyphicon-save'></span></a></td>";
				//echo "<td width='5%' class='text-center'><a href='?pg=".$pg."&acao=edit_lista&id=".$id."&ni=".$ni."&np=".$np."' title='Alterar'><span class='glyphicon glyphicon-pencil'></span></a></td>";
				echo "<td width='5%' class='text-center'><a href='$link_pg/del/".$id."' title='Apagar'><span class='glyphicon glyphicon-trash'></span></a></td>";
				echo "</tr>";
			
			}//fim while, loop
			//echo "<tr class='alerta'><td colspan=6 class='text-right'><a href='?pg=".$pg."&acao=cadastrar_lista' title='Adicionar nova lista'>Criar nova lista&nbsp;&nbsp;<span class='glyphicon glyphicon-plus'></span></a></td></tr>";
			

		}
		//?pg=contatos&acao=ver_lista&lista=1
		
		echo "</tbody></table>";

		$pagina_atual=$link_pg."/pag";

		if ($ultima_pag>1){
			echo "<div class='text-right'>";
			include("paginacao.php");
			echo "</div>";
		}	
		
	break;

	case "del";
		
		echo "apaga $var4";

	break;

}