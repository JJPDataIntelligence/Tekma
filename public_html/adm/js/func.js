
//adjust speed x=nr pixeli/loop , s = loop time in miliseconds
var x=8;
var s=5;
function goXleft(){
top.frames['principala'].window.scrollBy(-x,0);
goxl = setTimeout('goXleft()',s);
}
function goXright(){
top.frames['principala'].window.scrollBy(x,0);
goxr = setTimeout('goXright()',s);
}
function stopXleft(){
clearTimeout(goxl);
}
function stopXright(){
clearTimeout(goxr);
}


var nfiles = 1;
function Expand(){
  if(nfiles < 8) {
  var adh = dfile1.outerHTML;
  adh = adh.replace(/1/g,++nfiles)
  files.insertAdjacentHTML('BeforeEnd',adh);
  var img_tit =document.getElementById('img_tit'+nfiles).value=''
  var img_cred =document.getElementById('img_cred'+nfiles).value=''
  return false;
 }
};

function mostra_div(linha) {
var linha = document.getElementById(linha);
  if (linha.style.display=='none') {
   linha.style.display='';
  
  } else {
   linha.style.display='none';
  }
}

function check_visu_acess(id){
	var item = document.getElementsByClassName('item_acessorio_'+id)[0];
	if (document.getElementById('tem_acessorio_'+id).checked){
		document.getElementById('exibe_acessorio_'+id).removeAttribute("disabled");
		document.getElementById('exibe_acessorio_'+id).checked = true;
		item.style.fontWeight = "bold";
	}else{
		document.getElementById('exibe_acessorio_'+id).checked = false;
        //document.getElementById('exibe_acessorio_'+id).value=''; //Evita que o usuario defina um texto e desabilite o campo apos realiza-lo
		document.getElementById('exibe_acessorio_'+id).setAttribute("disabled", "disabled");
		item.style.fontWeight = "normal";
	}
}

function check_visu_eletrica(id){
	var item = document.getElementsByClassName('item_eletrica_'+id)[0];
	if (document.getElementById('tem_eletrica_'+id).checked){
		document.getElementById('exibe_eletrica_'+id).removeAttribute("disabled");
		document.getElementById('exibe_eletrica_'+id).checked = true;
		item.style.fontWeight = "bold";
	}else{
		document.getElementById('exibe_eletrica_'+id).checked = false;
		document.getElementById('exibe_eletrica_'+id).setAttribute("disabled", "disabled");
		item.style.fontWeight = "normal";
	}
}

function check_visu_instru(id){
	var item = document.getElementsByClassName('item_instru_'+id)[0];
	if (document.getElementById('tem_instru_'+id).checked){
		document.getElementById('exibe_instru_'+id).removeAttribute("disabled");
		document.getElementById('exibe_instru_'+id).checked = true;
		item.style.fontWeight = "bold";
	}else{
		document.getElementById('exibe_instru_'+id).checked = false;
		document.getElementById('exibe_instru_'+id).setAttribute("disabled", "disabled");
		item.style.fontWeight = "normal";
	}
}


function RefreshImage(valImageId) {
	var objImage = document.getElementById(valImageId)
	if (objImage == undefined) {
		return;
	}
	var now = new Date();
	objImage.src = objImage.src.split('?')[0] + '?x=' + now.toUTCString();
}

function pessoa(tipo){
	if(tipo=="fisica"){
		document.getElementById("pf").style.display = "inline";
		document.getElementById("pj").style.display = "none";
	}else if(tipo=="juridica"){
		document.getElementById("pf").style.display = "none";
		document.getElementById("pj").style.display = "inline";
	}
}


function validar(obj) { // recebe um objeto
	var s = (obj.value).replace(/\D/g,'');
	var tam=(s).length; // removendo os caracteres n??o numericos
	//if (!(tam==11 || tam==14 || tam==0)){ // validando o tamanho
	if (!(tam==11 || tam==14)){ // validando o tamanho
		alert("'"+s+"' N??o ?? um CPF ou um CNPJ v??lido!" ); // tamanho invalido
		return false;
	}
	
// se for CPF
	if (tam==11 ){
		if (!validaCPF(s)){ // chama a fun????o que valida o CPF
			alert("'"+s+"' N??o ?? um CPF v??lido!" ); // se quiser mostrar o erro
			obj.select();  // se quiser selecionar o campo em quest??o
			return false;
		}
		//alert("'"+s+"' ?? um CPF v??lido!" ); // se quiser mostrar que validou		
		obj.value=maskCPF(s);	// se validou o CPF mascaramos corretamente
		return true;
	}
	
// se for CNPJ			
	if (tam==14){
		if(!validaCNPJ(s)){ // chama a fun????o que valida o CNPJ
			alert("'"+s+"' N??o ?? um CNPJ v??lido!" ); // se quiser mostrar o erro
			obj.select();	// se quiser selecionar o campo enviado
			return false;			
		}
		//alert("'"+s+"' ?? um CNPJ v??lido!" ); // se quiser mostrar que validou				
		obj.value=maskCNPJ(s);	// se validou o CNPJ mascaramos corretamente
		return true;
	}
}
// fim da funcao validar()

// fun????o que valida CPF
// O algoritimo de valida????o de CPF ?? baseado em c??lculos
// para o d??gito verificador (os dois ??ltimos)
// N??o entrarei em detalhes de como funciona
function validaCPF(s) {
	var c = s.substr(0,9);
	var dv = s.substr(9,2);
	var d1 = 0;
	for (var i=0; i<9; i++) {
		d1 += c.charAt(i)*(10-i);
 	}
	if (d1 == 0) return false;
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(0) != d1){
		return false;
	}
	d1 *= 2;
	for (var i = 0; i < 9; i++)	{
 		d1 += c.charAt(i)*(11-i);
	}
	d1 = 11 - (d1 % 11);
	if (d1 > 9) d1 = 0;
	if (dv.charAt(1) != d1){
		return false;
	}
    return true;
}

// Fun????o que valida CNPJ
// O algoritimo de valida????o de CNPJ ?? baseado em c??lculos
// para o d??gito verificador (os dois ??ltimos)
// N??o entrarei em detalhes de como funciona
function validaCNPJ(CNPJ) {
	var a = new Array();
	var b = new Number;
	var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
	for (i=0; i<12; i++){
		a[i] = CNPJ.charAt(i);
		b += a[i] * c[i+1];
	}
	if ((x = b % 11) < 2) { a[12] = 0 } else { a[12] = 11-x }
	b = 0;
	for (y=0; y<13; y++) {
		b += (a[y] * c[y]);
	}
	if ((x = b % 11) < 2) { a[13] = 0; } else { a[13] = 11-x; }
	if ((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){
		return false;
	}
	return true;
}


	// Fun????o que permite apenas teclas num??ricas
	// Deve ser chamada no evento onKeyPress desta forma
	// return (soNums(event));
function soNums(e)
{
	if (document.all){var evt=event.keyCode;}
	else{var evt = e.charCode;}
	if (evt <20 || (evt >47 && evt<58)){return true;}
	return false;
}

//	fun????o que mascara o CPF
function maskCPF(CPF){
	return CPF.substring(0,3)+"."+CPF.substring(3,6)+"."+CPF.substring(6,9)+"-"+CPF.substring(9,11);
}

//	fun????o que mascara o CNPJ
function maskCNPJ(CNPJ){
	return CNPJ.substring(0,2)+"."+CNPJ.substring(2,5)+"."+CNPJ.substring(5,8)+"/"+CNPJ.substring(8,12)+"-"+CNPJ.substring(12,14);	
}


function zera(nro,max) {
    var todosSelect = document.getElementsByTagName("select");    
    for( x = nro; x < max; x++ ) {
        todosSelect[x].value = "1";
		todosSelect[x].style.display='none';
    }
}


function MascaraPeso(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
	var sep = 0;
	var key = '';
	var i = j = 0;
	var len = len2 = 0;
	var strCheck = '0123456789';
	var aux = aux2 = '';
	var whichCode = (window.Event) ? e.which : e.keyCode;
	if (whichCode == 13 || whichCode == 8) return true;
	key = String.fromCharCode(whichCode); // Valor para o c??digo da Chave
	if (strCheck.indexOf(key) == -1) return false; // Chave inv??lida
	len = objTextBox.value.length;
	//alert(len);
	for(i = 0; i < len; i++)
	if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;
	aux = '';
	for(; i < len; i++)
	if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);
	aux += key;
	len = aux.length;
	// alert(len);
	if (len == 12){
	return false;
	}
	if (len == 0) objTextBox.value = '';
	if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + '0'  + aux;
	if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;
	if (len == 3) objTextBox.value = '0'+ SeparadorDecimal +  aux;
	//if (len == 4) objTextBox.value = '0'+ SeparadorDecimal + aux;
	if (len > 3) {
	aux2 = '';
	for (j = 0, i = len - 4; i >= 0; i--) {
	if (j == 3) {
	aux2 += SeparadorMilesimo;
	j = 0;
	}
	aux2 += aux.charAt(i);
	//alert(aux2);
	j++;
	}
	objTextBox.value = '';
	//alert('1');
	len2 = aux2.length;
	//alert('2');
	for (i = len2 - 1; i >= 0; i--)
	objTextBox.value += aux2.charAt(i);
	objTextBox.value += SeparadorDecimal + aux.substr(len - 3, len);
	//alert(aux.substr(len - 4, len));
	}
	return false;
} 

/* VALIDA????O DE FORMUL??RIOS */


if( jQuery.browser.mozilla ) {
	// do when DOM is ready
	$( function() {
		// search form, hide it, search labels to modify, filter classes nocmx and error
		$( 'form.contato' ).hide().find( 'p>label:not(.nocmx):not(.error)' ).each( function() {
			var $this = $(this);
			var labelContent = $this.html();
			var labelWidth = document.defaultView.getComputedStyle( this, '' ).getPropertyValue( 'width' );
			// create block element with width of label
			var labelSpan = $("<span>")
				.css("display", "block")
				.width(labelWidth)
				.html(labelContent);
			// change display to mozilla specific inline-box
			$this.css("display", "-moz-inline-box")
				// remove children
				.empty()
				// add span element
				.append(labelSpan);
		// show form again
		}).end().show();
	});
};



				//$.validator.setDefaults({
				//	submitHandler: function() { alert("Dados Enviados com sucesso!"); }
				//});


$.metadata.setType("attr", "validate");
$(document).ready(function(){


 
	//Default Action
	$(".tab_content").hide(); //Hide all content
	$("ul.tabs li:first").addClass("active").show(); //Activate first tab
	$(".tab_content:first").show(); //Show first tab content
	
	//On Click Event
	$("ul.tabs li").click(function() {
		$("ul.tabs li").removeClass("active"); //Remove any "active" class
		$(this).addClass("active"); //Add "active" class to selected tab
		$(".tab_content").hide(); //Hide all tab content
		var activeTab = $(this).find("a").attr("href"); //Find the rel attribute value to identify the active tab + content
		$(activeTab).fadeIn(); //Fade in the active content
		return false;
	});
 


  ///M??scaras

	$(".moeda").maskMoney({showSymbol:true,symbol:"R$", decimal: ",", thousands: ".", allowZero:true});
  	$(".moeda_cx").maskMoney({showSymbol:false,symbol:"", decimal: ",", thousands: ".", allowZero:true});
	$(".data").mask("99/99/9999");
	$(".hora").mask("99:99");
	$(".tel").mask("(99) 9999-9999");
	$(".cel").focusout(function(){
		var phone, element;
		element = $(this);
		element.unmask();
		phone = element.val().replace(/\D/g, '');
		if(phone.length > 10) {
			element.mask("(99) 99999-999?9");
		} else {
			element.mask("(99) 9999-9999?9");
		}
	}).trigger('focusout');

	$(".cpf").mask("999.999.999-99");
	$(".cnpj").mask("99.999.999/9999-99"); 
	$(".cep").mask("99999-999");


	$(".periodo_de").mask("99/99/9999");
	$(".periodo_ate").mask("99/99/9999");
	$(".h_check_in").mask("99:99");
	$(".h_check_out").mask("99:99");

	
	$('#selectAll').click(function() {
		if(this.checked == true){
			$("input[type=checkbox]").each(function() { 
				this.checked = true; 
			});
		} else {
			$("input[type=checkbox]").each(function() { 
				this.checked = false; 
			});
		}
	});



	function countchecked() {
		// conta check
		  var n = $("input:checked").length;
		 // if (n <= 1) { n==0 } else { n=n-1 } ;
		  $("#anunciantes div.check").text("Estabelecimenos selecionados: (" + n + (n == 1 ? "" : "") + ")");
		}
		countchecked();
	$(":checkbox").click(countchecked);



	
	$("#contato").validate({
		errorLabelContainer: $("#contato div.error"),
		wrapper: 'li'
		
	});

	$("#formulario").validate({
		errorLabelContainer: $("#formulario div.error"),
		wrapper: 'li'
		
	});

	$("#login1").validate({
		errorLabelContainer: $("#login1 div.error1"),
		wrapper: 'li'
		
	});

	$("#login2").validate({
		errorLabelContainer: $("#login2 div.error2"),
		wrapper: 'li'
		
	});

	$("#lembrar_senha").validate({
		errorLabelContainer: $("#lembrar_senha div.error_senha"),
		wrapper: 'li'
		
	});



	$("#fisica").validate({
		errorLabelContainer: $("#fisica div.error"),
		wrapper: 'li'
		
	});


	$("#juridica").validate({
		errorLabelContainer: $("#juridica div.error"),
		wrapper: 'li'
		
	});

	$("anuncio").validate({
		errorLabelContainer: $("#anuncio div.error"),
		wrapper: 'li'
		
	});



}); // fim do $(document).ready(function() {	