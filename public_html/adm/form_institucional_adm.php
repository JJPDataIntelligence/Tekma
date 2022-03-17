<?php
if ($pg_int <> "S"){
	$redir="Location:index_adm.php";
	header($redir);
}

$tabela="<p></p>
<table align='center' style='width:100%'>
	<tbody>
		<tr>
			<td style='width: 25%; vertical-align: top;' valign=top>O LOFT :</td>
			<td style='width: 25%; vertical-align: top;'>&nbsp;</td>
			<td style='width: 25%; vertical-align: top;'>&nbsp;</td>
			<td style='width: 25%; vertical-align: top;'>&nbsp;</td>
		</tr>
	</tbody>
</table>
<p></p>
<table align='center' style='width:100%'>
	<tbody>
		<tr>
			<td style='width: 25%; vertical-align: top;' valign=top>A SUÍTE:</td>
			<td style='width: 25%; vertical-align: top;'>&nbsp;</td>
			<td style='width: 25%; vertical-align: top;'>&nbsp;</td>
			<td style='width: 25%; vertical-align: top;'>&nbsp;</td>
		</tr>
	</tbody>
</table>
";

/* if form
*************************************************************************************/
$meta_tag="S";


//include("if_form_adm.php");
$e_data="N";
$e_cat="N"; // pra vincular segmento ao conteúdo
$e_texto="S";
$e_imagem="S";
$e_link="N";
$e_sobre_rodape="N";


if ($acao=="edit" and $id=="1"){$e_texto="N";}

if (($acao=="cadastrar" and $id=="1") or $np=="1"){
	$e_chamada="S";
	$e_texto="N";
	$e_link="S";
	$meta_tag="N";
}else{
	$e_texto="S";
}

//Blog / ID=6

if (($acao=="cadastrar" and $id=="2") or $np=="2"){
	$e_data="S";
	$e_chamada="S";
	$e_texto="S";
	$e_video="S";
	//$e_link="S";
	$permissao_sub_top="N";
}


/*
// Fotos
if (($acao=="cadastrar" and $id=="2") or $np=="2"){
	$e_chamada="N";
	$e_texto="N";
	$meta_tag="N";
}
*/
// Tarifas
/*
if ($id=="7" and $acao<>"cadastrar"){
	$e_periodo="N";
	$e_txt_sup="S";
	$e_txt_inf="S";
	$e_chamada="N";
	$e_texto="N";
	$e_imagem="N";
	}
if ($acao=="cadastrar" and $id=="7"){$e_periodo="N";$e_imagem="N";$texto=$tabela;}
if ($np=="7"){$e_periodo="N";$e_imagem="N";}
*/
// Pacotes
/*
if ($id=="8" and $acao<>"cadastrar"){
	$e_periodo="N";
	$e_txt_sup="S";
	$e_txt_inf="S";
	$e_chamada="N";
	$e_texto="N";
	$e_imagem="N";
	}
if ($acao=="cadastrar" and $id=="8"){$e_periodo="S";$e_imagem="N";}
if ($np=="8"){$e_periodo="S";$e_imagem="N";}




if ($e_texto=="S" and $e_texto_simples=="S"){
	$ms="ERRO:Os dois tipos de texto est&atilde;o selecionados.";
	$e_texto="N";
	$e_texto_simples="S";
}
*/

/* fim if form
*************************************************************************************/



echo "<br>";

if ($alerta=="S"){
	echo "	<div class='col-sm-12'><p class='btn btn-danger'>".$alerta."</p></div>";
}


include ("form_adm.php");
