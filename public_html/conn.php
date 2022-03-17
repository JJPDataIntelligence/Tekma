<?php
function conecta(){

	if($conn){unset($conn);}

	$conn_host = 'tekma_db.mysql.dbaas.com.br';
	$conn_banco = 'tekma_db';
	$conn_user = 'tekma_db';
	$conn_senha = 'B@nco*Tekma19';

	try {
	  $conn = new PDO("mysql:host=$conn_host;dbname=$conn_banco;charset=utf8",$conn_user,$conn_senha);
	  $conn->exec("set names utf8");
	  //$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	} 
	catch(PDOException $e) {
		echo 'Erro: '.$e->getMessage();
	}
	return $conn;
}

function fecha_conn(){
	unset($conn);
}	
