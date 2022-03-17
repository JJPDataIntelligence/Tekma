<?php
if ($pg_int <> "S"){
	$redir="Location:index_adm.php";
	header($redir);
}
//$data = date('d-m-Y');
//echo $acao;

if ($alerta=="S"){
	echo "	<div class='col-sm-12'><p class='btn btn-danger'>".$alerta."</p></div>";
}

if ($e_periodo=="S"){
	?>
	<div class='form-group'>
		<div class='col-sm-1 control-label'><b>Período&nbsp;de</b></div>
		<div class='col-sm-4'>
			<div class="input-daterange input-group">
				<input type="text" class="form-control periodo_de" name="periodo_de" id="periodo_de" value="<?php echo $periodo_de;?>">
				<span class="input-group-addon">a</span>
				<input type="text" class="form-control periodo_ate" name="periodo_ate" id="periodo_ate" value="<?php echo $periodo_ate;?>">
			</div>
		</div>
		<div class='col-sm-7 control-label'>&nbsp;</div>
		<div class='clear'></div>
	</div>
	
	<?php
}

if ($e_data=="S"){
	?>
	<div class='form-group'>
		<div class='col-sm-1'><p class='f_titulo'><b>Data</b></p></div>
		<div class='col-sm-2'><input class='form-control data' name="data" id="data" style='width:85px;' value="<?php echo $data;?>" type="text" required></div>
		<!-- validate="{required:true, dateBR:true}" -->
		<div class='col-sm-9 control-label'>&nbsp;</div>

	</div>
	<div class='clear'></div>
	<?php
}

if ($permissao_sub_top=="S")	{
	?>
	<div class="form-group">
		<div class="col-sm-1"><p class='f_titulo'><b>Nome</b></p></div>
		<div class="col-sm-5"><input type="text" class="form-control" name="topico" id="topico" value="<?php echo $topico;?>" required></div>
		<!-- validate="{required:true, messages:{required:'Preencha o nome do Tópico'}}" -->
			
		<div class="col-sm-2">	
			<select class="form-control" name="sub_top" id="sub_top" required> 
			<!-- validate="{required:true, messages:{required:'Selecione a existência de subtópico'}}" -->
			<option value="" selected><b>Subtópico</b></option>
			<option value="S"<?php If ($sub_top=="S"){ echo "selected";}?>>Sim</option>
			<option value="N"<?php If ($sub_top=="N"){ echo "selected";}?>>Não</option>
			</select>
			<div style='display:none'>
				<input type='file' class='form-control' name='img[]' value=''>
				<input type='text' class='form-control' name='img_tit[]'>
				<input type='text' class='form-control' name='tp_img[]'>
				<input type='text' class='form-control' name='img_desc[]'>
				<input type='text' class='form-control' name='img_cred[]'>
			</div>
		</div>
			
		<div class='col-sm-1 control-label'><p class='f_titulo'><b>Status</b></p></div>	
		<div class='col-sm-2'>	
			<select class='form-control' name='status' id='status' required>";
				<?php
				echo "<option value='A'";
				If ($status=='A'){ echo ' selected';}
				echo ">Ativa</option>";
				echo "		<option value='N'";
				If ($status=='N'){ echo ' selected';}
				echo ">Desabilitada</option>";
				echo "		<option value='D'";
				If ($status=='D'){ echo ' selected';}
				echo ">Em desenvolvimento</option>";
				?>
			</select>
		</div>	
	</div>
	<?php
}else{
	?>
	<div class="form-group">
		<div class="col-sm-1"><p class='f_titulo'><b>Nome</b></p></div>
		<div class="col-sm-7"><input type="text" class="form-control" name="topico" id="topico" value="<?php echo $topico;?>" validate="{required:true, messages:{required:'Preencha o nome do Tópico'}}"></div>

		<div class='col-sm-1 control-label'><p class='f_titulo'><b>Status</b></p></div>	
		<div class='col-sm-3'>	
			<select class='form-control' name='status' id='status' required>
				<?php
				echo "<option value='A'";
				If ($status=='A'){ echo ' selected';}
				echo ">Ativa</option>";
				echo "		<option value='N'";
				If ($status=='N'){ echo ' selected';}
				echo ">Desabilitado</option>";
				echo "		<option value='D'";
				If ($status=='D'){ echo ' selected';}
				echo ">Em desenvolvimento</option>";
				?>
			</select>
		</div>	
			
	</div>
	<input type="hidden" name="sub_top" value="<?php echo $sub_top;?>">
	<?php
}


echo "<div id='div_conteudo'>";
// text-left active

	if ($e_txt_sup=="S"){
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Texto superior</b></p></div>";
		echo "	<div class='col-sm-12'><textarea class='form-control editor_p' id='chamada' name='chamada' wrap='virtual' required>".$chamada."</textarea></div>";
		echo "</div>";
	}

	if ($e_txt_inf=="S"){
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Texto inferior</b></p></div>";
		echo "	<div class='col-sm-12'><textarea class='form-control editor_p2' name='texto' id='texto' wrap='virtual'>".$texto."</textarea></div>";
		echo "</div>";
	}
/**/

	if ($e_chamada=="S"){
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Chamada</b></p></div>";
		echo "	<div class='col-sm-12'><textarea class='form-control editor_p' id='chamada' name='chamada' wrap='virtual'>".$chamada."</textarea></div>";
		echo "</div>";
	}

	if ($e_texto_simples=="S"){
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Texto</b></p></div>";
		echo "	<div class='col-sm-12'><textarea class='form-control' name='texto_simples' id='texto_simples' wrap='virtual' style='height:320px;'>".$texto."</textarea></div>";
		echo "</div>";
	}

	if($cont_link=="S"){
		if ($radio=="link"){
			$dis_link="inline";
			$dis_texto="none";
				//echo "<div class='form-group'>";
			echo "	<div class='col-sm-12 control-label'>";
			echo "		<input type='radio' name='radio' class='radio-inline' value='link' onclick='radio_cont(this.value);' checked>Link externo&nbsp;&nbsp;";
			echo "		<input type='radio' name='radio' class='radio-inline' value='texto' onclick='radio_cont(this.value);'>Conte&uacute;do interno";
			echo "	</div>";
				//echo "</div>";
		}else{
			$dis_link="none";
			$dis_texto="inline";
				//echo "<div class='form-group'>";
			echo "	<div class='col-sm-12 control-label'>";
			echo "		<input type='radio' name='radio' class='radio-inline' value='link' onclick='radio_cont(this.value);'>Link externo&nbsp;&nbsp;";
			echo "		<input type='radio' name='radio' class='radio-inline' value='texto' onclick='radio_cont(this.value);' checked>Conte&uacute;do interno";
			echo "	</div>";
				//echo "</div>";
		}

		echo "<div class='form-group'><div id='div_link' style='display:".$dis_link.";'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Link</b></p></div>";
		echo "	<div class='col-sm-12'><input type='text' class='form-control' name='link' id='link' value='".$link."'></div>";
		echo "</div>";


		echo "<div class='form-group'><div id='div_texto' style='display:".$dis_texto.";'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Conte&uacute;do</b></p></div>";
		echo "	<div class='col-sm-12'><textarea class='form-control editor' name='texto' id='texto' wrap='virtual'>".$texto."</textarea></div>";
		echo "</div>";
	}else{	

		if ($e_texto=="S"){
			echo "<div class='form-group'>";
			echo "	<div class='col-sm-12'><p class='f_titulo'><b>Conte&uacute;do</b></p></div>";
			echo "	<div class='col-sm-12'><textarea class='form-control editor' name='texto' id='texto' wrap='virtual'>".$texto."</textarea></div>";
			echo "</div>";
		}

		if ($e_link=="S"){
			echo "<div class='form-group'>";
			echo "	<div class='col-sm-12'><p class='f_titulo'><b>Link</b></p></div>";
			echo "	<div class='col-sm-12'><input type='text' class='form-control' name='link' id='link' value='".$link."'></div>";
			echo "</div><br>";
		}
	}//cont_link



	if ($e_sobre_rodape=="S"){
		echo "<hr><br><br><div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Sobre</b> (texto curto no rodapé do site)</p></div>";
		echo "	<div class='col-sm-12'><textarea class='form-control editor_p' name='chamada' id='chamada' wrap='virtual' style='height:320px;'>".$chamada."</textarea></div>";
		echo "</div>";
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='f_titulo'><b>Link</b></p></div>";
		echo "	<div class='col-sm-12'><input type='text' class='form-control' name='link' id='link' value='".$link."'></div>";
		echo "</div><hr><br><br>";
	}


	if ($e_video=="S"){
		$ex=htmlspecialchars('<iframe width="560" height="315" src="//www.youtube.com/embed/BqhZMh6dQNM" frameborder="0" allowfullscreen></iframe>', ENT_QUOTES);
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='bg text-center'><strong>Vídeo</strong>";
		//echo "		<br><span style='font-size:11px;'><b>Ex Youtube:</b>".$ex."</span></p>";
		echo "	</div>";
		echo "	<div class='col-sm-1 control-label'><b>Vídeo</b></div>";
		echo "	<div class='col-sm-11'><input type='text' class='form-control' name='video' id='video' value='".$video."'></div>";
		echo "</div>";
	}
	
	
	if ($e_imagem=="S"){

		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='bg text-center'><strong>Imagens</strong>";

		If ($var3<>"cadastrar"){
			?>
			&nbsp;-&nbsp;
			<a href="#" title='Reordenar imagens' onClick="window.open('<?php echo $host_adm;?>/reordena_imagem_adm.php?id=<?php echo $id;?>&tabela=imagens&top_ref=<?php echo $top_ref;?>','Janela','toolbar=no,location=yes,directories=yes,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=580,height=600'); return false;"><i class="social fa fa-random" aria-hidden="true"></i></a>
			</p>
			<?php
		}
		
		echo "</p></div>";
		echo "</div>";

		If ($var3<>"cadastrar"){
			echo "<div class='form-group'>";
			echo "	<div class='col-sm-12'>";
			echo "		<table class='table' height='".$conf_alt_p."'><tbody><tr>";
			echo "			<td bgcolor='#e8e8e8' width='14'><a href='#' onMouseOver='goXleft()' onMouseOut='stopXleft()'><img src='$host/img_site/rolagem_esquerda.gif' width='14' height='".$conf_alt_p."' border='0' alt=''></a></td>";
			echo "			<td bgcolor='#f4f4f4'><iframe name='principala' frameborder='0' width='100%' height='".$conf_alt_p."' scrolling='no' src='$host_adm/fotos_adm.php?top_ref=".$top_ref."&id_ref=".$id."'></iframe></td>";
			echo "			<td bgcolor='#e8e8e8' width='14'><a href='#' onMouseOver='goXright()' onMouseOut='stopXright()'><img src='$host/img_site/rolagem_direito.gif' width='14' height='".$conf_alt_p."' border='0' alt=''></a></td>";
			echo "		</tbody></tr></table>";
			echo "	</div>";
			echo "</div>";
		}


		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'>";
		echo "		<div id=files >";
		echo "			<div id='dfile1'>";
		echo "				<div class='col-sm-5'>Imagem:<input type='file' class='form-control' name='img[]'></div>";
		echo "				<div class='col-sm-5'>Titulo:<input type='text' class='form-control' name='img_tit[]'></div>";
		/*
		echo "				<div class='col-sm-2'>Tipo:";
		echo "					<select class='form-control' style='padding-left:2px;' name='tp_img[]'>";
		echo "						<option value='img'>Imagem</option>";
		echo "						<option value='arq'>Arquivo</option>";
		echo "					</select>";
		echo "				</div>";
		*/
		echo "				<!-- Crédito:<input type='text' class='form-control' name='img_cred[]'> -->&nbsp;";
		echo "				<div class='col-sm-2 text-right'><input type='button' value='+ Imagens' OnClick='return(Expand())'></div>";
		echo "			</div>";
		echo "		</div>";
		echo "	</div>";
		echo "</div>";
		echo "<div class='form-group'>";
		echo "	<div class='col-sm-12'><p class='bg' style='height:3px;'></p></div>";
		echo "</div>";

	}

	if ($meta_tag=="S"){

		echo "<div class='form-group'>";
		?><div class='col-sm-12'><p class='bg text-left'>&nbsp;&nbsp;<b>Otimização SEO</b>&nbsp;<input type='checkbox' name='mostra_meta' value='0' onclick="mostra_div('mostra_meta');"></p></div><?php
		echo "</div>";
		echo "<div class='form-group' id='mostra_meta' style='display:none;'>";

			//echo "<div class='form-group'>";
			echo "	<div class='col-sm-12'><b><a href='#' class='tooltips' data-toggle='tooltip' data-placement='top' title='Título - 70 caracteres ou menos' data-original-title='Título - 70 caracteres ou menos'>Título</a></b></div>";
			echo "	<div class='col-sm-12'><input type='text' class='form-control' name='title' id='title' value='".$title."'></div>";
			//echo "</div>";
		
			//echo "<div class='form-group'>";
			echo "	<div class='col-sm-12'><b><a href='#' data-toggle='tooltip' data-placement='top' title='Descrição - Texto objetivo de 150 caracteres ou menos' data-original-title='Descrição - Texto objetivo de 150 caracteres ou menos'>Descrição</a></b></div>";
			echo "	<div class='col-sm-12'><textarea class='form-control' name='description' id='description' wrap='virtual' style='height:60px;'>".$description."</textarea></div>";
			//echo "</div>";

			//echo "<div class='form-group'>";
			echo "	<div class='col-sm-12'><b><a href='#' class='tooltips' data-toggle='tooltip' data-placement='top' title='Recomenda-se utilizar três (ou menos) palavras-chave altamente segmentados ou frases separadas por vírgulas.' data-original-title='Recomenda-se utilizar três (ou menos) palavras-chave altamente segmentados ou frases separados por vírgulas.'>Palavras Chave</a></b></div>";
			echo "	<div class='col-sm-12'><textarea class='form-control' name='keywords' id='keywords' wrap='virtual' style='height:60px;'>".$keywords."</textarea></div>";
			//echo "</div>";

		echo "</div>";

	}

echo "</div>";


echo "<div class='clear'></div>";
echo "<div class='error'></div>";
echo "<div class='clear'></div>";


if ($volta==""){$volta=$link_pg."/ver/".$id;}

echo "<div class='form-group'>";
echo "	<div class='col-sm-12'><p class='text-right'><a href='".$volta."'><button type='button' class='btn btn-default'>Voltar</button></a>&nbsp;&nbsp;&nbsp;<button type='submit' name='submit' id='submit' class='btn btn-default'><b>Enviar</b></button></p></div>";
echo "<div class='clear'></div>";
echo "</div>";

