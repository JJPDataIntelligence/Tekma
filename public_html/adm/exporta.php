<?php
session_start();
if (!isset($_SESSION['admin'])) {
    session_destroy();
	unset ($_SESSION['sess']);
	unset ($_SESSION['id_adm']);
	unset ($_SESSION['admin']);
	unset ($_SESSION['nivel']);
	unset ($_SESSION['loja']);
    header('location:'.$host.'/adm/autentica.php');
}else{
	$session_sess=$_SESSION['sess'];
	$session_id_adm=$_SESSION['id_adm'];
	$session_admin=$_SESSION['admin'];
	$session_nivel=$_SESSION['nivel'];
	$session_loja=$_SESSION['loja'];

	include("../conn.php");
	include("funcoes.php");

	$arquivo="emails_".date('d-m-Y-H-i-s');

	header("Content-type: application/csv"); 
	header("Content-Disposition: attachment; filename=$arquivo.csv"); 
	header("Pragma: no-cache"); 

	$sql = "select nome,email,celular from emails group by email order by nome,email";
	$sql = mysql_query($sql) or die ('Erro ao tentar acessar a tabela');
	while ($rs = mysql_fetch_assoc($sql))
	{
		echo implode(';', $rs);
		echo "\n";
	}
}
?>