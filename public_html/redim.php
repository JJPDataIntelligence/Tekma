<?php
//redim.php?img=foto.jpg mostra a imagem original
//redim.php?img=foto.jpg&larg=600 mostra a imagem com largura de 600 e altura proporcional
//redim.php?img=foto.jpg&alt=200 mostra a imagem com altura de 200 e largura proporcional
//redim.php?img=foto.jpg&larg=640&alt=480 redimensiona a imagem sem crop para caber no tamanho e preenche o espaço com o rgb indicado. 
//redim.php?img=foto.jpg&larg=240&alt=480&crop=s redimensiona a imagem com crop
//redim.php?img=foto.jpg&larg=240&alt=480&tp=s pra mostrar a imagem distorcida no determinado tamanho
//se tp e crop tiverem valor o crop predomina

require('lib/manipula_img.php');

$imagem = $_GET['img'];
$l = $_GET['larg'];
$a = $_GET['alt'];
$c = $_GET['crop'];
$t = $_GET['tp'];
if ($c=="" and $t==""){$t='preenchimento';}//

if ($c<>""){$t='crop';}//

$img = new canvas('img_up/'.$imagem);
$img->rgb(255,255,255);
$img->redimensiona( $l, $a, $t );	
$img->posicaoCrop(70, 60, 100, 200);
$img->grava();
?>