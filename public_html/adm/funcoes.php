<?php

function grava_log($historico,$id_registro,$tabela,$acao){
	$data= date('Y-m-d H:i:s');
	$usuario=$_SESSION['id_adm'];
	$tp_user=$_SESSION['nivel'];
	$historico=str_replace("'","´",$historico);
	$acao=str_replace("'","´",$acao);
	$ip = $_SERVER["REMOTE_ADDR"];
	
	$conn=conecta();
	try {
		$sql="insert into logs (data,usuario,tp_user,historico,id_registro,tabela,acao,ip) values (:data,:usuario,:tp_user,:historico,:id_registro,:tabela,:acao,:ip)";
		$ins = $conn->prepare($sql);
		$ins -> bindParam(':data',$data,PDO::PARAM_STR);
		$ins -> bindParam(':usuario',$usuario,PDO::PARAM_STR);
		$ins -> bindParam(':tp_user',$tp_user,PDO::PARAM_STR);
		$ins -> bindParam(':historico',$historico,PDO::PARAM_STR);
		$ins -> bindParam(':id_registro',$id_registro,PDO::PARAM_STR);
		$ins -> bindParam(':tabela',$tabela,PDO::PARAM_STR);
		$ins -> bindParam(':acao',$acao,PDO::PARAM_STR);
		$ins -> bindParam(':ip',$ip,PDO::PARAM_STR);
		$ins -> execute();

	}catch(PDOException $e) {
	  echo 'Erro: '.$e->getMessage();
	}
}

function avisa($historico,$id_registro,$tabela,$acao){
	
	$data= date('Y-m-d H:i:s');
	$usuario=$_SESSION['admin'];
	$tp_user=$_SESSION['nivel_adm'];
	$historico=str_replace("'","´",$historico);
	$acao=str_replace("'","´",$acao);
	$ip = $_SERVER["REMOTE_ADDR"];

	$assunto=$historico;

	$msn="<html>
<body><font face=arial size=2>
<b>Id:</b>$id_registro<br>
<b>Tabela:</b>$tabela<br>
<b>Ação:</b>$acao<br>
</body>
</html>";

	$nome=str_replace("'","´",$nome);
	$email=str_replace("'","´",$email);
	$msn=str_replace("'","´",$msn);

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->Host = $sv;
	$mail->SMTPAuth = true;
	$mail->Username = $ea;
	$mail->Password = $sa;

	$mail->From = $ea;
	$mail->FromName = $nome_site;

	$mail->AddAddress($email_site);
	$mail->AddReplyTo($email,$nome);
	$mail->IsHTML(true); 
	$mail->Subject  = $assunto;
	$mail->Body = $msn; 

	$mail->AltBody = $mensagem_txt;

	$enviado = $mail->Send();

}

/* ================================================
* Função para resgatar os dados da API HG Brasil
*
* Parametros:
*
* parametros: array, informe os dados que quer enviar para a API
* chave: string, informe sua chave de acesso
* endpoint: string, informe qual API deseja acessar, padrao weather (previsao do tempo)
*/
function hg_request($parametros, $chave = null, $endpoint = 'weather'){
	$url = 'https://api.hgbrasil.com/'.$endpoint.'/?format=json&';
	if(is_array($parametros)){
		// Insere a chave nos parametros
		if(!empty($chave)) $parametros = array_merge($parametros, array('key' => $chave));
		// Transforma os parametros em URL
		foreach($parametros as $key => $value){
			if(empty($value)) continue;
			$url .= $key.'='.urlencode($value).'&';
		}
		// Obtem os dados da API
		$resposta = file_get_contents(substr($url, 0, -1));
		return json_decode($resposta);
	} else {
		return false;
	}
}



function estatisticas($id_anuncio,$tipo,$click){
	
	//click pode ser: 0:Exibição - 1:whats - 2:tel - 3:email
	$ip = getenv("REMOTE_ADDR");
	$data = date('Y-m-d H:i:s');
	$usuario=$_SESSION['usuario'];
	if($usuario==""){$usuario=0;}


	if($id_anuncio<>"" and $tipo<>"" and $click<>""){
		$conn=conecta();
		$sql='insert into estatisticas (data,usuario,anuncio,tipo,click,ip) values (:data,:usuario,:anuncio,:tipo,:click,:ip)';
		$ins = $conn->prepare($sql);
		$ins -> bindParam(':data',$data,PDO::PARAM_STR);
		$ins -> bindParam(':usuario',$usuario,PDO::PARAM_STR);
		$ins -> bindParam(':anuncio',$id_anuncio,PDO::PARAM_STR);
		$ins -> bindParam(':tipo',$tipo,PDO::PARAM_STR);
		$ins -> bindParam(':click',$click,PDO::PARAM_STR);
		$ins -> bindParam(':ip',$ip,PDO::PARAM_STR);
		$ins -> execute();
	}else{
		echo "Erro: Dados incompletos para estatística.";
	}
}

/*
function estatisticas($tp_anuncio,$id_anuncio,$campo){
// PRECISA ARRUMAR ISSO...tem 
	list ($dia, $mes, $ano) = explode ('-', date('d-m-Y'));
	
	$sql_e="select * from estatisticas where mes='".$mes."' and ano='".$ano."' and tipo='".$tp_anuncio."' and id_anuncio='".$id_anuncio."'";
	$sql_e=mysqli_query($conn,$sql_e);
	if (mysqli_num_rows($sql_e) == 0) {	
		$chave = Sorteia(20).$_SERVER["REMOTE_ADDR"];
		$ins="insert into estatisticas (mes,ano,tipo,id_anuncio,exibicao,hotsite,clique,nro_tel,email) values ('".$mes."','".$ano."','".$chave."','".$id_anuncio."','0','0','0','0','0')";
		$ins=mysqli_query($conn,$ins) or die(mysqli_error());

		$ins="select * from estatisticas where mes='".$mes."' and ano='".$ano."' and tipo='".$chave."' and id_anuncio='".$id_anuncio."'";
		$log=$ins;
		$ins = mysqli_query($conn,$ins) or die(mysqli_error());
		if (mysqli_num_rows($ins) == 0){
			grava_log("Erro nas estatísticas",'0','estatisticas',$log);
		}else{
			$rs_est = mysqli_fetch_array($ins);
			$id_est = $rs_est['id'];
			$val_campo = $rs_est[$campo]+1;
			$at="update estatisticas set tipo='".$tp_anuncio."',id_anuncio='".$id_anuncio."',".$campo."='".$val_campo."' where id='".$id_est."'";
			//echo "<!-- ".$at." / 61 -->";
			$at=mysqli_query($conn,$at) or die(mysqli_error());
		}
	}else{
		$rs_est = mysqli_fetch_array($sql_e);
		$id_est = $rs_est['id'];
		$val_campo = $rs_est[$campo]+1;
		$at="update estatisticas set tipo='".$tp_anuncio."',id_anuncio='".$id_anuncio."',".$campo."='".$val_campo."' where id='".$id_est."'"; 
		//echo "<!-- ".$at." / 68 -->";
		$at=mysqli_query($conn,$at) or die(mysqli_error());
	}
}
*/


function calc_digito($num){
	if (strlen($num) == 12){
		/* Compute the EAN-13 Checksum digit */
		$ncode = $num;
		$even = 0; $odd = 0;
		for ($x=0;$x<12;$x++) {
		if ($x % 2) { $odd += $ncode[$x]; } else { $even += $ncode[$x]; }
		}
		$code =(10 - (($odd * 3 + $even) % 10)) % 10;
		
	}else{
		$code="Erro";
	}
	return $code;
}

function monta_cod_barra($num){
	if (strlen($num) == 12){
		/* Compute the EAN-13 Checksum digit */
		$ncode = $num;
		$even = 0; $odd = 0;
		for ($x=0;$x<12;$x++) {
		if ($x % 2) { $odd += $ncode[$x]; } else { $even += $ncode[$x]; }
		}
		$code =(10 - (($odd * 3 + $even) % 10)) % 10;
		return $num.$code;
	}else{
		echo "Erro";
	}
}

Function RemoveAcentos($string){
	 $string = preg_replace('/[\t\n]/', ' ', $string);
    $string = preg_replace('/\s{2,}/', ' ', $string);
    $list = array(
        'Š' => 'S',
        'š' => 's',
        'Đ' => 'Dj',
        'đ' => 'dj',
        'Ž' => 'Z',
        'ž' => 'z',
        'Č' => 'C',
        'č' => 'c',
        'Ć' => 'C',
        'ć' => 'c',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'A',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ý' => 'Y',
        'Þ' => 'B',
        'ß' => 'Ss',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'a',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'o',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ý' => 'y',
        'ý' => 'y',
        'þ' => 'b',
        'ÿ' => 'y',
        'Ŕ' => 'R',
        'ŕ' => 'r',
        '/' => '-',
        ' ' => '-',
        '.' => '-',
    );

    $string = strtr($string, $list);
    $string = preg_replace('/-{2,}/', '-', $string);
    //$string = strtolower($string);
    return $string;
}


function smartURL($str){
    $str = strtolower(utf8_decode($str)); $i=1;
    $str = strstr($str, utf8_decode('àáâãäåæçèéêëìíîïñòóôõöøùúûýýÿ'), 'aaaaaaaceeeeiiiinoooooouuuyyy');
    $str = preg_replace("/([^a-z0-9])/",'-',utf8_encode($str));
    while($i>0) $str = str_replace('--','-',$str,$i);
    if (substr($str, -1) == '-') $str = substr($str, 0, -1);
    return $str;
}

Function url_amigavel($string) {

	//https://sounoob.com.br/slugify-converter-texto-em-slug-com-php/#code

    $string = preg_replace('/[\t\n]/', ' ', $string);
    $string = preg_replace('/\s{2,}/', ' ', $string);
    $list = array(
        'Š' => 'S',
        'š' => 's',
        'Đ' => 'Dj',
        'đ' => 'dj',
        'Ž' => 'Z',
        'ž' => 'z',
        'Č' => 'C',
        'č' => 'c',
        'Ć' => 'C',
        'ć' => 'c',
        'À' => 'A',
        'Á' => 'A',
        'Â' => 'A',
        'Ã' => 'A',
        'Ä' => 'A',
        'Å' => 'A',
        'Æ' => 'A',
        'Ç' => 'C',
        'È' => 'E',
        'É' => 'E',
        'Ê' => 'E',
        'Ë' => 'E',
        'Ì' => 'I',
        'Í' => 'I',
        'Î' => 'I',
        'Ï' => 'I',
        'Ñ' => 'N',
        'Ò' => 'O',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ö' => 'O',
        'Ø' => 'O',
        'Ù' => 'U',
        'Ú' => 'U',
        'Û' => 'U',
        'Ü' => 'U',
        'Ý' => 'Y',
        'Þ' => 'B',
        'ß' => 'Ss',
        'à' => 'a',
        'á' => 'a',
        'â' => 'a',
        'ã' => 'a',
        'ä' => 'a',
        'å' => 'a',
        'æ' => 'a',
        'ç' => 'c',
        'è' => 'e',
        'é' => 'e',
        'ê' => 'e',
        'ë' => 'e',
        'ì' => 'i',
        'í' => 'i',
        'î' => 'i',
        'ï' => 'i',
        'ð' => 'o',
        'ñ' => 'n',
        'ò' => 'o',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ö' => 'o',
        'ø' => 'o',
        'ù' => 'u',
        'ú' => 'u',
        'û' => 'u',
        'ý' => 'y',
        'ý' => 'y',
        'þ' => 'b',
        'ÿ' => 'y',
        'Ŕ' => 'R',
        'ŕ' => 'r',
        '/' => '-',
        ' ' => '-',
        '.' => '-',
    );

    $string = strtr($string, $list);
    $string = preg_replace('/-{2,}/', '-', $string);
    $string = strtolower($string);

    return $string;
}
function pega_url($URL){ 
    $ch = curl_init (); 
	$timeout = 10;
    curl_setopt ( $ch , CURLOPT_RETURNTRANSFER ,  1 ); 
    curl_setopt ( $ch , CURLOPT_URL , $URL );
	curl_setopt ( $ch , CURLOPT_CONNECTTIMEOUT, $timeout);
	curl_setopt ( $ch , CURLOPT_TIMEOUT, $timeout); 
	curl_setopt ( $ch , CURLOPT_USERAGENT, $_SERVER["HTTP_USER_AGENT"]);  
    $data = curl_exec ( $ch ); 
    curl_close ( $ch ); 
    return $data ; 
		
//echo pega_url('http://example.com');
}


function validaEmail($email) {
	$conta = "^[a-zA-Z0-9\._-]+@";
	$domino = "[a-zA-Z0-9\._-]+.";
	$extensao = "([a-zA-Z]{2,4})$";

	$pattern = $conta.$domino.$extensao;

	if (ereg($pattern, $email))	{
		return true;
	}else{
		return false;
	}
}


function verifica_dominio($dominio,$tipo){
	return checkdnsrr($dominio,$tipo);
}

function pega_imagem ( $image_url ){ 
	$ch = curl_init();
	$timeout = 10;
	curl_setopt ($ch, CURLOPT_URL, $image_url);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

	// Getting binary data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);

	$image = curl_exec($ch);
	curl_close($ch);

	// output to browser
	header("Content-type: image/jpeg");
	return $image;
}

function RemoveHTML($document){ 
	$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript 
				   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags 
				   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly 
				   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA 
	); 
	$text = preg_replace($search, '', $document); 
	return $text; 
} 

function ValidaData($dat){
	$data = explode("/","$dat"); // fatia a string $dat em pedados, usando / como referência
	$d = $data[0];
	$m = $data[1];
	$y = $data[2];
 
	// verifica se a data é válida!
	// 1 = true (válida)
	// 0 = false (inválida)
	$res = checkdate($m,$d,$y);
	if ($res == 1){
	   //echo "data ok!";
	   return true;
	} else {
	   //echo "data inválida!";
	   return false;
	}
}
 
//Exemplo de chamada a função
//ValidaData("31/02/2002")

function Sorteia ($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
	// tamanho default = 8
	// Caracteres de cada tipo
	$lmin = 'abcdefghijklmnopqrstuvwxyz';
	$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$num = '1234567890';
	$simb = '!@#$%*-';

	// Variáveis internas
	$retorno = '';
	$caracteres = '';

	// Agrupamos todos os caracteres que poderão ser utilizados
	$caracteres .= $lmin;
	if ($maiusculas) $caracteres .= $lmai;
	if ($numeros) $caracteres .= $num;
	if ($simbolos) $caracteres .= $simb;

	// Calculamos o total de caracteres possíveis
	$len = strlen($caracteres);

	for ($n = 1; $n <= $tamanho; $n++) {
	// Criamos um número aleatório de 1 até $len para pegar um dos caracteres
	$rand = mt_rand(1, $len);
	// Concatenamos um dos caracteres na variável $retorno
	$retorno .= $caracteres[$rand-1];
	}

	return $retorno;

	// Gera uma senha com 10 carecteres: letras (min e mai), números Ex: gfUgF3e5m7
	// $senha = Sorteia(10);

	// Gera uma senha com 9 carecteres: letras (min e mai) Ex: BJnCYupsN
	// $senha = Sorteia(9, true, false);


	// Gera uma senha com 6 carecteres: letras minúsculas e números	Ex: sowz0g
	// $senha = Sorteia(6, false, true);

	// Gera uma senha com 15 carecteres de números, letras e símbolos  Ex: fnwX@dGO7P0!iWM
	// $senha = Sorteia(15, true, true, true);
}
 
?>