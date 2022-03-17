<?php
$charset="UTF-8";
//$charset="ISO-8859-1";
header("Content-type: text/html; charset=".$charset,true);
setlocale(LC_ALL, "pt_BR");
setlocale(LC_MONETARY,"pt_BR", "ptb");

/*
foreach($_POST as $nome_campo => $valor){ 
   echo "\$" . $nome_campo . "='" . $valor . "';"; 
} 
*/
$envia=$_POST['envia'];

switch ($envia){

	case "contato":

		$envia="Ok";

		$nome=$_POST['nome'];
		$email=$_POST['email'];
		$telefones=$_POST['telefones'];
		$mensagem_txt = $_POST['mensagem'];
		$mensagem = nl2br($_POST['mensagem']);

		$assunto=$_POST['assunto'];
		if($assunto==""){$assunto="Contato";}

$msn="<html><head><meta http-equiv='content-type' content='text/html; charset=$charset'></head>
<body><font face=arial size=2>
Dados enviados através do formulário de contato do site.<br><br>
<b>Nome:</b>$nome<br>
<b>Email:</b>$email<br>
<b>Telefone(s):</b>$telefones<br>
<b>Mensagem:</b>$mensagem<br>
</body>
</html>";		

	break;

	case "news":

		//$envia="Ok";
		if($assunto==""){$assunto="Newsletter";}

		$email=$_POST['email'];
		$i_email=str_replace("'","´",$email);
		$i_data = date('Y-m-d H:i:s');
		$i_status="A";

		include("conn.php");
		$conn=conecta();

		$sql = 'insert into newsletter (data,email,status) values(:i_data,:i_email,:i_status)';
		$ins = $conn->prepare($sql);
		$ins -> bindParam(':i_data',$i_data,PDO::PARAM_STR);
		$ins -> bindParam(':i_email',$i_email,PDO::PARAM_STR);
		$ins -> bindParam(':i_status',$i_status,PDO::PARAM_STR);
		$ins -> execute();

		$envio="<div class='alert alert-success alert-dismissible' role='alert'>";
		$envio=$envio."<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
		$envio=$envio."E-mail cadastrado com sucesso.";
		$envio=$envio."</div>";

		echo $envio;

	break;

}//switch


if($envia=="Ok"){

	require("mailer/class.phpmailer.php"); 	

	$sv="mail.tekma.com.br";
	$ea="site@tekma.com.br";
	$sa="Form*159";
	$nome_site="Tekma";
	$email_site="tekma@tekma.com.br";

	//$email_site="andre@portalhosting.com.br";

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
	$mail->CharSet = 'UTF-8';
	$mail->AltBody = $mensagem_txt;
	$enviado = $mail->Send();

	$mail->ClearAllRecipients();
	$mail->ClearAttachments();

	if ($enviado) {
		$envio="<div class='alert alert-success alert-dismissible' role='alert'>";
		$envio=$envio."<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
		$envio=$envio."Mensagem enviada com sucesso.";
		$envio=$envio."</div>";
	} else {
		$envio="<div class='alert alert-danger alert-dismissible' role='alert'>";
		$envio=$envio."<button type='button' class='close' data-dismiss='alert'><span aria-hidden='true'>&times;</span><span class='sr-only'>Fechar</span></button>";
		$envio=$envio."Erro ao enviar:".$mail->ErrorInfo;
		$envio=$envio."</div>";
	}

	echo $envio;
}
?>