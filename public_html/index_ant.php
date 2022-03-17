<?php

$url=$_GET['url'];

list ($var1, $var2, $var3, $var4) = explode ('/', $url);

$charset="utf-8";

//$charset="ISO-8859-1";



header('Content-type: text/html; charset=.$charset.');

setlocale(LC_ALL, "pt_BR");

setlocale(LC_ALL, "pt_BR");

setlocale(LC_MONETARY,"pt_BR", "ptb");

session_start();



$diretorio="img_up/"; 



$title_site="";

$description_site="";

$keywords_site="";

$host="";

$script="";



include("envio.php");



$txt_premissa1="Com mais de 30 anos de experiência em projetos de engenharia ligados à mobilidade para grandes empresas, principalmente do setor automotivo, os profissionais da <b>TEKMA</b> tem expertise no desenvolvimento de produtos de excelência em inovação e tecnologia adequados para competir no acirrado mercado global.<br><br>";

$txt_premissa2="Com habilidades específicas adquiridas ao longo de suas carreiras, a equipe <b>TEKMA</b> consegue compreender as necessidades dos clientes e prover soluções completas em serviços de engenharia para as mais variadas demandas da mobilidade.<br><br>";



$txt_conceito1="A <b>TEKMA</b> nasce no modelo de negócios de cooperativa de trabalho para a prestação de serviços de engenharia e correlatos, oferecendo especialistas nas principais áreas de conhecimento.";

$txt_conceito2="A identidade de propósitos e interesses assegura que os desenvolvimentos de projetos, protótipos, avaliações de conjuntos de componentes e veículos sejam realizados com a máxima eficácia técnica e com resultados de altíssima qualidade.";

$txt_conceito3="Além destas competências, a <b>TEKMA</b> oferece gestão de projetos nas áreas de pósvendas, logística, qualidade e processos.";



$txt_missao="Nossa missão é prover soluções aos nossos clientes em serviços da mobilidade de forma avançada, precisa e efetiva agregando valores pautados em competitividade, segurança e sustentabilidade.";

$txt_visao="A <b>TEKMA</b> aspira ser reconhecida com distinção como uma prestadora de serviços de engenharia ligados à mobilidade.";

$txt_valores="• Competência • Confiança • Eficiência • Ética • Excelência • Honestidade • Inovação • Qualidade • Responsabilidade social • Confidencialidade • Sustentabilidade.";



$txt_serv1="Consultoria e assessoria no gerenciamento de projetos de engenharia de produto com o uso de ferramentas da qualidade.";

$txt_serv2="Gerenciamento e execução de testes funcionais em parceria com o cliente ou empresas especializadas.";

$txt_serv3="Gerenciamento e assessoria em engenharia de serviços, treinamentos e literaturas técnicas no pós-venda.";

$txt_serv4="Gerenciamento e assessoria em testes de durabilidade, análises de falhas e de confiabilidade do produto.";

$txt_serv5="Treinamento em sistemas e ferramentas da qualidade e melhoria contínua.";

$txt_serv6="Consultoria e assessoria em conceituação de produtos e processos, inclusive com a elaboração de cadernos de conceito e encargos, além da execução de projetos com ferramentas usuais de mercado. ";



$equipe_1[0]="celso.jpg";

$equipe_1[1]="CELSO <span class='equipe_laranja'>MACARINI</span> DA COSTA";

$equipe_1[2]="celso.macarini@tekma.com.br";

$equipe_1[3]="Pós-graduação em Marketing de Serviços.<br>Especialização em Motores - E. E. Mauá / I.M.T.<br>Graduação em Engenharia Mecânica - FEI.<br>Desenvolvimento de combustão de motores diesel.<br>Calibração e aplicação de sistemas de alimentação de ar, injeção de combustível e pós tratamento de emissões.";

$equipe_1[4]="https://www.linkedin.com/in/celso-macarini-da-costa-76b67815";



$equipe_2[0]="dioraci.jpg";

$equipe_2[1]="<span class='equipe_laranja'>DIORACI</span> VIEIRA MACHADO";

$equipe_2[2]="dioraci.machado@tekma.com.br";

$equipe_2[3]="Pós-graduação em Administração da Produção.<br>Especialização em Motores - E. E. Mauá / I.M.T.<br>Graduação em Engenharia Mecânica - USP São Carlos.<br>Desenvolvimento de combustão de motores diesel.<br>Calibração e aplicação de sistemas de alimentação de ar, injeção de combustível e pós tratamento de emissões.";

$equipe_2[4]="";



$equipe_3[0]="ivo.jpg";

$equipe_3[1]="<span class='equipe_laranja'>IVO</span> AMADEU CINO ";

$equipe_3[2]="ivo.cino@tekma.com.br";

$equipe_3[3]="MBA Administração e Marketing - USP.<br>Graduação em Engenharia Mecânica Plena - E. E. Mauá / I.M.T.<br>Desenvolvimento de componentes do sistema chassis, gerenciamento e acompanhamento de testes funcionais e de durabilidade.<br>Desenvolvimento de marketing de produtos no setor automotivo.";

$equipe_3[4]="https://www.linkedin.com/in/ivoamadeucino/";



$equipe_4[0]="jose_augusto.jpg";

$equipe_4[1]="<span class='equipe_laranja'>JOSÉ AUGUSTO</span> PAGAN CAMPOS";

$equipe_4[2]="jose.augusto@tekma.com.br";

$equipe_4[3]="Especialização em Engenharia de Produção - FEI.<br>Graduação em Engenharia Mecânica - Automobilística - FEI.<br>Gerenciamento de projetos de veículos e de seus componentes, em todas as suas fases até a implantação em série.";

$equipe_4[4]="https://www.linkedin.com/in/jose-augusto-pagan-campos-60017028";



$equipe_5[0]="jose_aparecido.jpg";

$equipe_5[1]="<span class='equipe_laranja'>JOSÉ APARECIDO</span> PEREIRA";

$equipe_5[2]="jose.pereira@tekma.com.br";

$equipe_5[3]="Tecnólogo em Processo de Produção.<br>Desenvolvimento de componentes de motores diesel e sistemas de fixação, vedação e protetores térmicos.";

$equipe_5[4]="";



$equipe_6[0]="luiz_carlos.jpg";

$equipe_6[1]="LUIZ CARLOS <span class='equipe_laranja'>BACHIEGA</span>";

$equipe_6[2]="luiz.bachiega@tekma.com.br";

$equipe_6[3]="Graduação em Engenharia Mecânica - Automobilística - FEI.<br>Desenvolvimento de componentes de motores diesel e acompanhamento de ensaios em bancadas dinamométricas e em veículos em campo.";

$equipe_6[4]="https://www.linkedin.com/in/luiz-carlos-bachiega-71a4b3103";



$equipe_7[0]="luiz_henrique.jpg";

$equipe_7[1]="LUIZ HENRIQUE <span class='equipe_laranja'>BORGES</span>";

$equipe_7[2]="luiz.borges@tekma.com.br";

$equipe_7[3]="Especialização em Motores de Combustão Interna.<br>Graduação em Engenharia Mecânica - Ênfase Automobilística - FEI.<br>Desenvolvimento e aplicação de novos produtos no âmbito do trem de força de veículos comerciais.";

$equipe_7[4]="";



$equipe_8[0]="marcelo.jpg";

$equipe_8[1]="MARCELO MEDEIROS HAGE";

$equipe_8[2]="marcelo.hage@tekma.com.br";

$equipe_8[3]="Graduação em Física e Licenciatura em Matemática.<br>Análise e avaliações de acústica e vibrações.<br>Acompanhamento de ensaios em veículos.";

$equipe_8[4]="";



$equipe_9[0]="nelson.jpg";

$equipe_9[1]="NELSON DA SILVA <span class='equipe_laranja'>BENTO</span>";

$equipe_9[2]="nelson.bento@tekma.com.br";

$equipe_9[3]="MBA em Gestão Empresarial - Business School SP / Univ. Toronto.<br>Especialização em Tecnologia Mecânica - ETI Lauro Gomes.<br>Graduação em Administração de Empresas - UMESP.<br>Planejamento, gestão e implementação de estratégias na engenharia do produto, manufatura, sist. da qualidade e desenvolvimento de fornecedores.";

$equipe_9[4]="https://www.linkedin.com/in/nelson-bento-a868905";



$equipe_10[0]="paulo.jpg";

$equipe_10[1]="<span class='equipe_laranja'>PAULO SERGIO</span> PEREIRA DOS SANTOS";

$equipe_10[2]="psergio.santos@tekma.com.br";

$equipe_10[3]="Graduação em Engenharia Mecânica - UNIMEP/SP.<br>Desenvolvimento de componentes do sistema chassis, gerenciamento e acompanhamento de testes funcionais e de durabilidade.<br>Engenharia de serviços e desempenho do produto no pós-venda.";

$equipe_10[4]="https://www.linkedin.com/in/paulo-sergio-p-santos-090492104/";



$equipe_11[0]="rodrigo.jpg";

$equipe_11[1]="<span class='equipe_laranja'>RODRIGO</span> MARIANO LEÃO PARREIRA";

$equipe_11[2]="rodrigo.parreira@tekma.com.br";

$equipe_11[3]="MBA Executivo - INSPER CREA/SP.<br>Graduação em Engenharia Mecânica - Ênfase Automobilística - FEI.<br>Desenvolvimento de componentes de suspensão, direção e gerenciamento de projetos.";

$equipe_11[4]="https://www.linkedin.com/in/rodrigo-parreira-7840594";



$equipe_12[0]="walter.jpg";

$equipe_12[1]="<span class='equipe_laranja'>WALTER</span> BIAZETTI";

$equipe_12[2]="walter.biazetti@tekma.com.br";

$equipe_12[3]="Graduação em Engenharia de Produção Mecânica e Design.<br>Projeto de produto em sistemas CAD, CATIA-V5 e NX.";

$equipe_12[4]="https://www.linkedin.com/in/walter-biazetti-06198086 ";







if ($og_type==""){$og_type="website";}

if ($img_site==""){$img_site=$host."/img_site/logo.png";}

if ($zoom_site==""){$zoom_site=14;}	



if ($og_type==""){$og_type="website";}

if ($img_site==""){$img_site=$host."/img_site/logo.png";}

if ($temp_title_site<>""){$title_site=$temp_title_site;}

if ($temp_description_site<>""){$description_site=$temp_description_site;}

if ($temp_keywords_site<>""){$keywords_site=$temp_keywords_site;}





?>

<!DOCTYPE html>

<html prefix="og: https://ogp.me/ns#" lang="pt-br">

	<head>

		<title><?php echo $title_site;?></title>

		<base href="<?php echo $host."/".$url;?>" />

			

		<meta charset="ISO-8859-1">

		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<meta name="viewport" content="width=device-width, initial-scale=1">

		<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

		<meta name="keywords" content="<?php echo $keywords_site;?>">

		<meta name="description" content="<?php echo $description_site;?>">

		<meta name="image" content="<?php echo $img_site;?>" />

		<meta property="og:type" content="<?php echo $og_type;?>" />

		<meta property="og:url" content="<?php echo $host."/".$url;?>" />

		<meta property="og:title" content="<?php echo $title_site;?>" />

		<meta property="og:description" content="<?php echo $description_site;?>" />

		<meta property="og:image" content="<?php echo $img_site;?>" />

		<meta name="author" content="Portal Hosting Soluções Web - https://www.portalhosting.com.br">

		<link rel="stylesheet" href="<?php echo $host;?>/css/bootstrap.css">

		<link rel="stylesheet" href="<?php echo $host;?>/css/font-awesome.css">

		<link rel="stylesheet" href="<?php echo $host;?>/css/animate.css">

		<link rel="stylesheet" href="<?php echo $host;?>/css/carousel_site.css" type="text/css" media="screen">

		<link rel="stylesheet" href="<?php echo $host;?>/estilo.css" type="text/css" media="screen">



		<link href="https://fonts.googleapis.com/css?family=Oswald:600&display=swap" rel="stylesheet">



		

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->

		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

		<!--[if lt IE 9]>

		  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>

		  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

		<![endif]-->

	</head>



	<body>



		<nav class="navbar navbar-default navbar-fixed-top xsticky">

			<div class="container">

				<div class="navbar-header">

					<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">

						<span class="sr-only">Toggle navigation</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

					</button>

					<a class="navbar-brand" href="<?php echo $host;?>"><img src="img_site/logo.png" width="250" height="71" border="0" alt=""></a>

				</div>

						

				<div id="navbar" class="navbar-collapse collapse">

					<ul class="nav navbar-nav navbar-right">

					<!-- <ul class="nav nav-justified menu"> -->

                       <li><a href='#inicio' class="scrollto">INÍCIO</a></li>

					   <li><a href='#quem-somos' class="scrollto">QUEM SOMOS</a></li>

					   <li><a href='#servicos' class="scrollto">SERVIÇOS</a></li>

					   <li><a href='#equipe' class="scrollto">EQUIPE</a></li>

					   <li><a href='#contato' class="scrollto">CONTATO</a></li>

					 </ul>

				</div><!--/.nav-collapse -->

			</div>

		</nav>



		<div class='clear'></div>



		<section id='inicio' class='bg_conteudo'>

			<section id='slide'>

				<div id='carousel_1' class='carousel slide carousel-fade center'>

					<div class='carousel-inner'>

						<div class='item carousel-full active center' style='background-image: url(img_site/slide_1.jpg);'>

							<!-- <div class='carousel-caption'><p></p></div> -->

							<div class='clear'></div>

						</div>

						<div class='item carousel-full center' style='background-image: url(img_site/slide_2.jpg);'>

							<div class='clear'></div>

						</div>

						<div class='item carousel-full center' style='background-image: url(img_site/slide_3.jpg);'>

							<div class='clear'></div>

						</div>

					</div>

					<a class='left carousel-control' href='#carousel_1' data-slide='prev'><span class='glyphicon glyphicon-chevron-left'></span></a>

					<a class='right carousel-control' href='#carousel_1' data-slide='next'><span class='glyphicon glyphicon-chevron-right'></span></a>

				</div>

				<div class='clear'></div>

			</section>

			<div class='clear'></div>

			<a class='scroll-button scrollto' href='#quem-somos'><i class='fa fa-angle-down'></i></a>

			<div class='clear'></div>

			<?php $script.="$('#carousel_1').carousel({interval:4000,cycle: true});";?>

		</section>



		<div class='clear'></div>



		<section id='quem-somos' class='bg_conteudo'>

		

			<p class='topico text-center'><span class='laranja'>PRE</span>MISSA</p>

			<div class="container">

				<div class='col-sm-6 text-justify'><?php echo $txt_premissa1;?></div>

				<div class='col-sm-6 text-justify'><?php echo $txt_premissa2;?></div>

			</div>

			<br><br>

			<div class='bg_azul_img'>

				<div class="container">

					<p class='topico text-center'><span class='laranja'>CON</span>CEITO</p>

					<div class='col-sm-3'></div>

					<div class='col-sm-9'>

						

						<?php 

						echo "<p class='conceito'>$txt_conceito1</p>";

						echo "<p class='conceito'>$txt_conceito2</p>";

						echo "<p class='conceito'>$txt_conceito3</p>";

						?>

					</div>

				</div>

			</div>

			<br><br>

			<p class='topico text-center'><span class='laranja'>DIRE</span>TRIZES</p>

			<div class="container text-center">

				<div class='col-sm-4'><div class='diretriz'><br><span class='titulo'>MISSÃO</span><br><br><?php echo $txt_missao;?></div></div>

				<div class='col-sm-4'><div class='diretriz'><br><span class='titulo'>VISÃO</span><br><br><?php echo $txt_visao;?></div></div>

				<div class='col-sm-4'><div class='diretriz'><br><span class='titulo'>VALORES</span><br><br><?php echo $txt_valores;?></div></div>

			</div>

			<br><br>

			<img src="img_site/faixa_laranja.png" class='img-responsive'>

			<br><br>

		

		</section>



		<div class='clear'></div>



		<section id='servicos' class='bg_conteudo'>

			<p class='topico text-center'><span class='laranja'>SER</span>VIÇOS</p>



			<div class="container">

				<div class='col-sm-4'>

					<div class="urlImg"><div class='p20'><?php echo $txt_serv1;?></div></div>

				</div>

				<div class='col-sm-4'>

					<div class="urlImg"><div class='p20'><?php echo $txt_serv2;?></div></div>

				</div>

				<div class='col-sm-4'>

					<div class="urlImg"><div class='p20'><?php echo $txt_serv3;?></div></div>

				</div>

			</div>

			<div class="container">

				<div class='col-sm-4'>

					<div class="urlImg"><div class='p20'><?php echo $txt_serv4;?></div></div>

				</div>

				<div class='col-sm-4'>

					<div class="urlImg"><div class='p20'><?php echo $txt_serv5;?></div></div>

				</div>

				<div class='col-sm-4'>

					<div class="urlImg"><div class='p20'><?php echo $txt_serv6;?></div></div>

				</div>

			</div>

			



		</section>



		<div class='clear'></div>



		<section id='equipe' class='bg_conteudo'>



			<p class='topico text-center'><span class='laranja'>E</span>QUIPE</p>



			<div class="container">



				<div class='col-sm-12 mp0 mtop10'>

					<img src="img_site/mosaico_equipe.jpg" class='img-responsive'><br>

					<div class='clear'></div>

					<img src="img_site/texto_equipe.jpg" class='img-responsive' style='max-width:100%;float:right;'>

					<br><br><br><br>

				</div>



				<div class='clear'></div>





				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_1[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_1[1]";?></p>

							<?php if ($equipe_1[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_1[2]."'>".$equipe_1[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_1[3]";?></p>

							<?php if ($equipe_1[4]<>""){echo "<p class='text-right'><a href='".$equipe_1[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

					<div class='clear'></div>

				</div>

				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_2[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_2[1]";?></p>

							<?php if ($equipe_2[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_2[2]."'>".$equipe_2[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_2[3]";?></p>

							<?php if ($equipe_2[4]<>""){echo "<p class='text-right'><a href='".$equipe_2[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

				</div>



				<div class='clear'></div>





				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_3[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_3[1]";?></p>

							<?php if ($equipe_3[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_3[2]."'>".$equipe_3[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_3[3]";?></p>

							<?php if ($equipe_3[4]<>""){echo "<p class='text-right'><a href='".$equipe_3[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

					<div class='clear'></div>

				</div>

				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_4[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_4[1]";?></p>

							<?php if ($equipe_4[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_4[2]."'>".$equipe_4[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_4[3]";?></p>

							<?php if ($equipe_4[4]<>""){echo "<p class='text-right'><a href='".$equipe_4[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

				</div>



				<div class='clear'></div>





				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_5[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_5[1]";?></p>

							<?php if ($equipe_5[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_5[2]."'>".$equipe_5[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_5[3]";?></p>

							<?php if ($equipe_5[4]<>""){echo "<p class='text-right'><a href='".$equipe_5[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

					<div class='clear'></div>

				</div>

				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_6[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_6[1]";?></p>

							<?php if ($equipe_6[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_6[2]."'>".$equipe_6[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_6[3]";?></p>

							<?php if ($equipe_6[4]<>""){echo "<p class='text-right'><a href='".$equipe_6[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

				</div>



				<div class='clear'></div>





				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_7[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_7[1]";?></p>

							<?php if ($equipe_7[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_7[2]."'>".$equipe_7[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_7[3]";?></p>

							<?php if ($equipe_7[4]<>""){echo "<p class='text-right'><a href='".$equipe_7[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

					<div class='clear'></div>

				</div>

				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_8[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_8[1]";?></p>

							<?php if ($equipe_8[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_8[2]."'>".$equipe_8[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_8[3]";?></p>

							<?php if ($equipe_8[4]<>""){echo "<p class='text-right'><a href='".$equipe_8[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

				</div>



				<div class='clear'></div>



				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_9[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_9[1]";?></p>

							<?php if ($equipe_9[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_9[2]."'>".$equipe_9[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_9[3]";?></p>

							<?php if ($equipe_9[4]<>""){echo "<p class='text-right'><a href='".$equipe_9[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

					<div class='clear'></div>

				</div>

				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_10[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_10[1]";?></p>

							<?php if ($equipe_10[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_10[2]."'>".$equipe_10[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_10[3]";?></p>

							<?php if ($equipe_10[4]<>""){echo "<p class='text-right'><a href='".$equipe_10[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

				</div>



				<div class='clear'></div>



				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_11[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_11[1]";?></p>

							<?php if ($equipe_11[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_11[2]."'>".$equipe_11[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_11[3]";?></p>

							<?php if ($equipe_11[4]<>""){echo "<p class='text-right'><a href='".$equipe_11[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

					<div class='clear'></div>

				</div>

				<div class='col-sm-6 mp0 mtop10'>

					<div class="conteudo">

						<div class='col-sm-5'>

							<?php echo "<img src='img_site/$equipe_12[0]' class='img-responsive'>";?>

						</div>

						<div class='col-sm-7'>

							<p class='equipe_nome'><?php echo "$equipe_12[1]";?></p>

							<?php if ($equipe_12[2]<>""){echo "<p class='equipe_email'><a class='equipe_email' href='mailto:".$equipe_12[2]."'>".$equipe_12[2]."</a></p>";}?>

							<p class='equipe_texto'><?php echo "$equipe_12[3]";?></p>

							<?php if ($equipe_12[4]<>""){echo "<p class='text-right'><a href='".$equipe_12[4]."' target='_blank'><img src='img_site/linkedin.png'></a></p>";}?>

						</div>

						<div class='clear'></div>

					</div>

				</div>



				<div class='clear'></div>



			</div>





			<br><br>

			<img src="img_site/faixa_azul.png" class='img-responsive'>

			<br><br>



		</section>



		<div class='clear'></div>



		<section id='contato' class='bg_conteudo'>



			<div class="container">

				<div class='col-sm-4' style='height:50px;'><img src="img_site/ico_email.png" style='float:left;margin-right:4px;'>tekma@tekma.com.br</div>

				<div class='col-sm-4' style='height:50px;'><img src="img_site/ico_tel.png" style='float:left;margin-right:4px;'>11 5594.7684</div>

				<div class='col-sm-4' style='height:50px;'><img src="img_site/ico_end.png" style='float:left;margin-right:4px;'>R.Paranapanema, nº 187, Saúde<br>São Paulo | CEP 04144-100</div>

			</div>



			<br>



			<div class="container">

				<form method='post' action='' ENCTYPE='multipart/form-data' class='form-horizontal' name='formulario' id='formulario'>

					<input type="hidden" name="envia" value='contato'>

					<div class='col-sm-4'>

						<input type='text' class='form-control inputs' name='nome' id='nome' value='' placeholder='Seu Nome' required><br>

					</div>

					<div class='col-sm-4'>

						<input type='text' class='form-control inputs' name='telefones' id='telefone' value='' placeholder='Telefone'><br>

					</div>

					<div class='col-sm-4'>

						<input type='email' class='form-control inputs' name='email' id='email' value='' placeholder='E-mail' required><br>

					</div>

					

					<div class='col-sm-12'>

						<textarea class='form-control inputs' name='mensagem' id='mensagem' wrap='virtual' style='height:265px;' placeholder='Escreva aqui sua mensagem' required></textarea>

					</div>

															

					<div class='col-sm-6'></div>



					<div class='col-sm-6'>

						<div id='div_form'></div>	

						<p class='text-right'><br>

							<button type='submit' name='contato' value='ok' id='contato' class='btn btn-site botao'><b>ENVIAR</b></button>

						</p>

					</div>

				</form>



				<br><br>

			</div>

			



		</section>



		<footer>

			<div class="container">

			

				<form method='post' action='' ENCTYPE='multipart/form-data' class='form-horizontal' name='newsletter' id='form_news'>

					<input type="hidden" name="envia" value='news'>

					<div class='col-sm-8'><br>

						<img src="img_site/logo_rodape.png" width="250"><br>

						<span class='copy'>Copyright © 2019 TEKMA - Todos os direitos reservados</span>

					</div>

					

					<div class='col-sm-4'><div id='div_news' style='float:left'><p class='text-right'>&nbsp;</p></div>

						<input type='email' class='form-control input_news' name='email' id='email' value='' placeholder='Seu E-mail' required>

						<p class='text-right'><button type='submit' class='btn btn-site botao_news text-right'><b>ASSINAR<br>NEWSLETTER</b></button></p>

					</div>

				</form>

				

				<br><br>

			</div>

		</footer>







		<script src="<?php echo $host;?>/js/jquery.js"></script>

		<script src="<?php echo $host;?>/js/bootstrap.js"></script>

		<script src="<?php echo $host;?>/js/scrollto.js"></script>

			

		<script type='text/javascript'>

			var host = "<?php echo $host;?>/";

			$(document).ready(function(){<?php echo $script;?>});

		</script>



		<script>

		/*============================================

		ScrollTo Links

		==============================================*/

		$('a.scrollto').click(function(e){

			$('html,body').scrollTo(this.hash, this.hash, {gap:{y:-90}});//y=altura da diferença, um tipo de margem entre o div e o topo ou bottom, o que normalmente poderá ser a altura do menu (negativo)

			e.preventDefault();



			if ($('.navbar-collapse').hasClass('in')){

				$('.navbar-collapse').removeClass('in').addClass('collapse');

			}

		});





		/* toTop

		-------------------------------*/

		$('#toTop').click(function () {

			$("body,html").animate({scrollTop: 0}, 600);

			return false;

		});



		$(window).scroll(function () {

			if ($(this).scrollTop() != 0) {

				$("#toTop").fadeIn(300);

			} else {

				$("#toTop").fadeOut(250);

			}

		});





		/* Form Contato

		-------------------------------*/

		$('#formulario').submit(function(){	 

			$('#div_form').html("<b>Enviando...</b>");	 

			$.ajax({

				type: 'POST',

				url: host + 'envio.php', 

				data: $(this).serialize()

			})

			.done(function(data){

				$('#div_form').html(data);

			})

			.fail(function() {

				alert( "Falha no envio" ); 

			});

			 

			// to prevent refreshing the whole page page

			return false;

		 

		});







		/* Form Newsletter

		-------------------------------*/

		$('#form_news').submit(function(){	 

			$('#div_news').html("<b>Enviando...</b>");	 

			$.ajax({

				type: 'POST',

				url: host + 'envio.php', 

				data: $(this).serialize()

			})

			.done(function(data){

				$('#div_news').html(data);

			})

			.fail(function() {

				alert( "Falha no envio" ); 

			});

			 

			// to prevent refreshing the whole page page

			return false;

		 

		});



		</script>



		<!-- <script src="<?php echo $host;?>/scripts.js"></script> -->

	</body>

</html>



