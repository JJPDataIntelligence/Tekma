$(document).ready(function(){


/* AUTOCOMPLETA **********************************/
/*
	$("#_consulta_ex").focus().autocomplete("autocompleta.php?tp=ex",{
		minChars:1 //Número mínimo de caracteres para aparecer a lista
		, matchContains: true //Aparecer somente os que tem relação ao valor digitado
		, scrollHeight: 180 //Altura da lista dos nomes
		, selectFirst: true //Vir o primeiro da lista selecionado
		, mustMatch: false //Caso não existir na lista, remover o valor
		, delay: 0 //Tempo para aparecer a lista para 0, por padrão vem 200
	});	
	//Quando selecionar valor pegar retorno. O retorno nesse caso são: Nome|Código
	$("#_consulta_ex").result(function(event, retorno) {
		if (retorno==undefined)
			$("#cor").val("");
			//$("#bairro").val("");
			//$("#cidade").val("");
		else
			$("#cor").val(retorno[1]);
			//$("#bairro").val(retorno[2]);
			//$("#cidade").val(retorno[3]);
	});	
*/

	$("#b_cliente").focus().autocomplete("autocompleta.php?tp=cliente",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_cliente").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_cli").val("");
		else
			$("#id_cli").val(retorno[1]);
	});	


	$("#b_cliente_cx").focus().autocomplete("autocompleta.php?tp=cliente_cx",{
		minChars:1
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: true 
		, delay: 0
	});
				
	$("#b_cliente_cx").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_cli_cx").val("");
		else
			$("#id_cli_cx").val(retorno[1]);
	});	



	$("#b_produto").focus().autocomplete("autocompleta.php?tp=produto",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_produto").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_prod").val("");
		else
			$("#id_prod").val(retorno[1]);
	});	


	$("#b_produto_cx").focus().autocomplete("autocompleta.php?tp=produto_cx",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_produto_cx").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_prod_cx").val("");
		else
			$("#id_prod_cx").val(retorno[1]);
	});	


	$("#b_produto_mv").focus().autocomplete("autocompleta.php?tp=produto_mv",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_produto_mv").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_prod_mv").val("");
		else
			$("#id_prod_mv").val(retorno[1]);
	});	

	
	$("#b_ticket").focus().autocomplete("autocompleta.php?tp=ticket",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_ticket").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_ticket").val("");
		else
			$("#id_ticket").val(retorno[1]);
	});	



/*

	$("#consulta_cor").focus().autocomplete("autocompleta.php?tp=pega_cor",{
		minChars:1
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0 
	});
	$("#consulta_cor").result(function(event, retorno) {
		if (retorno==undefined)
			$("#cor").val("");
		else
			$("#cor").val(retorno[1]);;
	});	

	
	$("#consulta_cep").focus().autocomplete("autocompleta.php?tp=pega_cep",{
		minChars:1 
		, matchContains: true 
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false
		, delay: 0 
	});
	$("#consulta_cep").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_cep").val("");
		else
			$("#id_cep").val(retorno[1]);
	});	
	

	$("#produto_mov").focus().autocomplete("autocompleta.php?tp=pega_prod_mov",{
		minChars:1 
		, matchContains: false
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false
		, delay: 0 
	});		
	$("#produto_mov").result(function(event, retorno) {
		if (retorno==undefined)
			$("#cod_barra").val("");
		else
			$("#cod_barra").val(retorno[1]);
	});	

*/

/* Fim autocompeta **********************************************************/
/*
		$('#form_cta').submit(function(){
			$('#consumo').html("<b>Carregando...</b>");
			$.ajax({
				type: 'POST',
				url: 'cesta_consulta.php', 
				data: $(this).serialize()
			})
			.done(function(data){
				$('#consumo').html(data);
				document.getElementById("b_produto_consulta").value = '';
			})
			.fail(function() {
				alert( "Falha no envio" ); 
			});
			return false;
		});

		$('#form_data_vacina').submit(function(){
			$('#consumo').html("<b>Carregando...</b>");
			$.ajax({
				type: 'POST',
				url: 'cesta_consulta.php', 
				data: $(this).serialize()
			})
			.done(function(data){
				$('#consumo').html(data);
			})
			.fail(function() {
				alert( "Falha no envio" ); 
			});
			return false;
		});

*/


 
		$("select[name=depto]").change(function(){
			$("select[name=secao]").html('<option value="">Carregando...</option>');
			$("select[name=cat]").html('<option value=""></option>');
			$("select[name=subcat1]").html('<option value=""></option>');
//			$("select[name=subcat2]").html('<option value=""></option>');
//			$("select[name=subcat3]").html('<option value=""></option>');

			$.post("mostra_combo.php?m=secao",
				{id:$(this).val()},
					function(mostra_secao){
					$("select[name=secao]").html(mostra_secao);
				}
			)
		});


		$("select[name=secao]").change(function(){
			$("select[name=cat]").html('<option value="">Carregando...</option>');
			$("select[name=subcat1]").html('<option value=""></option>');
//			$("select[name=subcat2]").html('<option value=""></option>');
//			$("select[name=subcat3]").html('<option value=""></option>');
            $.post("mostra_combo.php?m=cat",
				{id:$(this).val()},
				function(mostra_cat){
					$("select[name=cat]").html(mostra_cat);
				}
			)   
		});

		$("select[name=cat]").change(function(){
			$("select[name=subcat1]").html('<option value="">Carregando...</option>');
//			$("select[name=subcat2]").html('<option value=""></option>');
//			$("select[name=subcat3]").html('<option value=""></option>');
            $.post("mostra_combo.php?m=subcat1",
				{id:$(this).val()},
				function(mostra_subcat1){
					$("select[name=subcat1]").html(mostra_subcat1);
				}
			)   
		});
/*
		$("select[name=subcat1]").change(function(){
			$("select[name=subcat2]").html('<option value="">Carregando...</option>');
			$("select[name=subcat3]").html('<option value=""></option>');
            $.post("mostra_combo.php?m=secao",
				{id:$(this).val()},
				function(mostra_subcat2){
					$("select[name=subcat2]").html(mostra_subcat2);
				}
			)   
		});
	 

		$("select[name=subcat2]").change(function(){
			$("select[name=subcat3]").html('<option value="">Carregando...</option>');
			$.post("mostra_combo.php?m=secao",
				{id:$(this).val()},
				function(mostra_subcat3){
					$("select[name=subcat3]").html(mostra_subcat3);
				}
			)
		});

*/




		$("select[name=loja1]").change(function(){
			$("select[name=loja2]").html('<option value="">Carregando...</option>');
			$.post("mostra_combo.php?m=transferencia",
				{id:$(this).val()},
				function(mostra_loja2){
					$("select[name=loja2]").html(mostra_loja2);
				}
			)
		});









		$("select[name=sexo]").change(function(){
			$("select[name=situacao]").html('<option value="">Carregando...</option>');
			$.post("mostra_combo.php?m=situacao",
				{id:$(this).val()},
				function(mostra_situacao){
					$("select[name=situacao]").html(mostra_situacao);
				}
			)
		});

/* Modal *******************************/
		$('#Modal').modal('show'); 


/* datepicker UI *******************************/
/*
$( "#datepicker" ).datepicker({
			inline: true
		});
		

		$.datepicker.regional['pt-BR'] = {
			closeText: 'Fechar',
			prevText: '&#x3C;Anterior',
			nextText: 'Próximo&#x3E;',
			currentText: 'Hoje',
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
			dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
			dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['pt-BR']);


	$( "#periodo_de" ).datepicker({
			defaultDate: "",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#periodo_ate" ).datepicker( "option", "minDate", selectedDate );
				//$("#selector").datepicker({minDate: '-14D', maxDate: '+1M'},$.datepicker.regional['pt-BR']);
		}
	});
	
	$( "#periodo_ate" ).datepicker({
			defaultDate: "+1d",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#periodo_de" ).datepicker( "option", "maxDate", selectedDate );
		}
	});

*/

/* datepicker Bootstrap *******************************/
/*
		$( "#datepicker" ).datepicker({
			inline: true
		});
		

		$.datepicker.regional['pt-BR'] = {
			closeText: 'Fechar',
			prevText: '&#x3C;Anterior',
			nextText: 'Próximo&#x3E;',
			currentText: 'Hoje',
			monthNames: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
			monthNamesShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
			dayNames: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'],
			dayNamesShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
			dayNamesMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
			weekHeader: 'Sm',
			dateFormat: 'dd/mm/yy',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: false,
			yearSuffix: ''};
		$.datepicker.setDefaults($.datepicker.regional['pt-BR']);


	$( "#periodo_de" ).datepicker({
			defaultDate: "",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#periodo_ate" ).datepicker( "option", "minDate", selectedDate );
				//$("#selector").datepicker({minDate: '-14D', maxDate: '+1M'},$.datepicker.regional['pt-BR']);
		}
	});
	
	$( "#periodo_ate" ).datepicker({
			defaultDate: "+1d",
			changeMonth: true,
			numberOfMonths: 1,
			onClose: function( selectedDate ) {
				$( "#periodo_de" ).datepicker( "option", "maxDate", selectedDate );
		}
	});

*/







}); //read function





/* claculo cx *******************************/
	
	function number_format( number, decimals, dec_point, thousands_sep ) {
	var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
	var d = dec_point == undefined ? "," : dec_point;
	var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
	var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
	return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	}


	function startCalc(){  // FUNÇÃO PARA CALCULAR CAMPOS NUMÉRICOS DO FORM
		interval = setInterval("calc()",1);
	}


	function calc(){

	compra = $('#compra').val().replace(".","").replace(",",".");
	dinheiro = $('#dinheiro').val().replace(".","").replace(",",".");
	//cartao_deb_master = $('#cartao_deb_master').val().replace(".","").replace(",",".");
	//cartao_cred_master = $('#cartao_cred_master').val().replace(".","").replace(",",".");
	//cartao_deb_visa = $('#cartao_deb_visa').val().replace(".","").replace(",",".");
	//cartao_cred_visa = $('#cartao_cred_visa').val().replace(".","").replace(",",".");
	cartao_deb_outros = $('#cartao_deb_outros').val().replace(".","").replace(",",".");
	cartao_cred_outros = $('#cartao_cred_outros').val().replace(".","").replace(",",".");
	cheque_vista = $('#cheque_vista').val().replace(".","").replace(",",".");
	//cheque_pre = $('#cheque_pre').val().replace(".","").replace(",",".");
	//cartao_deb = $('#cartao_deb').val().replace(".","").replace(",",".");
	//cartao_cred = $('#cartao_cred').val().replace(".","").replace(",",".");

	desconto = $('#desconto').val().replace(".","").replace(",",".");
	
	p_desc=$('#p_desc').val().replace(".","").replace(",",".");

	if (p_desc!='' && p_desc!=0){
		//desconto = ((100-p_desc)/100)*compra;
		desconto = (p_desc/100)*compra;
		document.recebimento.desconto.value = number_format(desconto, 2, ',', '.');
	}

	//receita = (dinheiro * 1) + (cheque_vista * 1) + (cheque_pre * 1) + (cartao_deb * 1) + (cartao_cred * 1) + (desconto * 1);
	//receita = (dinheiro * 1) + (cartao_deb_master * 1) + (cartao_cred_master * 1) + (cartao_deb_visa * 1) + (cartao_cred_visa * 1) + (cartao_deb_outros * 1) + (cartao_cred_outros * 1) + (cheque_vista * 1) + (desconto * 1);
	receita = (dinheiro * 1) + (cartao_deb_outros * 1) + (cartao_cred_outros * 1) + (cheque_vista * 1) + (desconto * 1);

	total = (receita - compra);

	var resultado = document.getElementById("troco").innerHTML=number_format(total, 2, ',', '.');
		if (total < 0) {
			var cor_resultado = "<span style='color:#ff0000;font-size:17px;'>" + resultado + "</span>";
		}else {
			var cor_resultado = "<span style='color:#0000ff;font-size:17px;'>" + resultado + "</span>";
		}
	document.getElementById('troco').innerHTML = cor_resultado;
	// coloca o valor total no input em formato da moeda real
	//resultado = $('#total').val(number_format(total, 2, ',', '.'));	
	//document.getElementById("demo").innerHTML=number_format(total, 2, ',', '.');

	//$('#total').val(number_format(total, 2, ',', '.'));	
	}

	function stopCalc(){
	  clearInterval(interval);
	}

