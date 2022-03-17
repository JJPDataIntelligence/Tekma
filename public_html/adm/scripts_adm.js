$(document).ready(function(){

/* EDITOR **********************************/ 
	var config_p = {width:"100%",height:150};
	$('.editor_p').ckeditor(config_p);

	var config_p2 = {width:"100%",height:100};
	$('.editor_p2').ckeditor(config_p2);

	var config = {width:"100%",height:280};
	$('.editor').ckeditor(config);
					

/* AUTOCOMPLETA **********************************/ 

	$("#b_cliente").focus().autocomplete(host + "/adm/autocompleta.php?tp=cliente",{
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

	$("#b_cliente_anuncio").focus().autocomplete(host + "/adm/autocompleta.php?tp=cliente_anuncio",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_cliente_anuncio").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_cli_anuncio").val("");
		else
			$("#id_cli_anuncio").val(retorno[1]);
	});	



	$("#b_anuncio").focus().autocomplete(host + "/adm/autocompleta.php?tp=anuncio",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: true 
		, delay: 0
	});
				
	$("#b_anuncio").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_busca_cat").val("");
		else
			$("#id_busca_cat").val(retorno[1]);
	});	



	$("#b_local").focus().autocomplete(host + "/adm/autocompleta.php?tp=local",{
		minChars:2
		, matchContains: true
		, scrollHeight: 180
		, selectFirst: true
		, mustMatch: false 
		, delay: 0
	});
				
	$("#b_local").result(function(event, retorno) {
		if (retorno==undefined)
			$("#id_local").val("");
		else
			$("#id_local").val(retorno[1]);
	});	
	

	



/* Combo **********************************************************/

	$('#form_news').submit(function(){
		$('#response').html("<b>Enviando...</b>");
		//$('#response').html('');	 
		$.ajax({
			type: 'POST',
			url: host + '/newsletter.php', 
			data: $(this).serialize()
		})
		.done(function(data){
			$('#response').html(data);	 
		})
		.fail(function() {
			alert( "Falha no envio" ); 
		});
		return false; 
	});


	$("select[name=combo_uf]").change(function(){
		$("select[name=combo_cidade]").html('<option value="">Carregando...</option>');
        $.post(host +"/adm/mostra_combo.php?m=combo_cidade",
			{id:$(this).val()},
			function(mostra_combo_cidade){
				$("select[name=combo_cidade]").html(mostra_combo_cidade);
			}
		)   
	});

	
	$("select[name=categoria]").change(function(){
		$("select[name=subcategoria]").html('<option value="">Carregando...</option>');
        $.post(host +"/adm/mostra_combo.php?m=subcategoria",
			{id:$(this).val()},
			function(mostra_subcategoria){
				$("select[name=subcategoria]").html(mostra_subcategoria);
			}
		)   
	});




	$("select[name=uf_anuncio]").change(function(){
		$("select[name=cidade_anuncio]").html('<option value="">Carregando...</option>');
		$("select[name=bairro_anuncio]").html('<option value=""></option>');
		$.post(host + "/adm/mostra_combo.php?m=cidade_anuncio",
			{id:$(this).val()},
			function(mostra_cidade_anuncio){
				$("select[name=cidade_anuncio]").html(mostra_cidade_anuncio);
			}
		)
	});

	$("select[name=cidade_anuncio]").change(function(){
		$("select[name=bairro_anuncio]").html('<option value="">Carregando...</option>');
        $.post(host +"/adm/mostra_combo.php?m=bairro_anuncio",
			{id:$(this).val()},
			function(mostra_bairro_anuncio){
				$("select[name=bairro_anuncio]").html(mostra_bairro_anuncio);
			}
		)   
	});



	



	


	$("select[name=f_estaleiro]").change(function(){
		$("select[name=f_ano]").html('<option value="">Carregando...</option>');
		$("select[name=f_tamanho]").html('<option value=""></option>');
		$.post(host +"/adm/mostra_combo.php?m=f_ano",
			{id:$(this).val()},
			function(mostra_f_ano){
				$("select[name=f_ano]").html(mostra_f_ano);
			}
		)
	});

	$("select[name=f_ano]").change(function(){
		$("select[name=f_tamanho]").html('<option value="">Carregando...</option>');
        $.post(host +"/adm/mostra_combo.php?m=f_tamanho",
			{id:$(this).val()},
			function(mostra_f_tamanho){
				$("select[name=f_tamanho]").html(mostra_f_tamanho);
			}
		)   
	});





/* Tooltip,Popover,Modal *******************************/
	$('[data-toggle="tooltip"]').tooltip(); 
	$('[data-toggle="popover"]').popover(); 
	
	$('#Modal').modal('show'); 
	


	$('.indica-modal').on('click',function(){
		var id=$(this).data('id');
		//alert(id);
		$('.modal-content').html('<b>Carregando...</b>');
		   $.ajax({
			type: 'POST',
			url: host +'/adm/indica_modal.php',
			data:{id: id},
			success: function(data_indica) {
			  $('.modal-content').html(data_indica);
			},
			error:function(err_indica){
			  alert("Não foi possível carregar o arquivo");
			}
		})
	 });

	

	$('.reordena-imagem-modal').on('click',function(){
		var id=$(this).data('id');
		//alert(id);
		$('.modal-content').html('<b>Carregando...</b>');
		   $.ajax({
			type: 'POST',
			url: host +'/adm/reordena_imagem_adm.php',
			data:{id: id},
			success: function(data_venda) {
			  $('.modal-content').html(data_venda);
			},
			error:function(err_venda){
			  alert("Não foi possível carregar o arquivo");
			}
		})
	 });

	


/* Datepicker *******************************/

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



/* read function *************************/
}); //read function

