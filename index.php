<?php 
	// CHANGED HERE TO ADD NEW readcooperados FILE REQUIREMENT 
	include_once(__DIR__."/conn.php");
	include_once(__DIR__."/connection.php");
	require_once(__DIR__."/model/Member.php");
	require_once(__DIR__."/model/BusinessUnit.php");
?>

<?php 
	$charset = "UTF-8";

	$url = $_GET['url'];
	list ($var1, $var2, $var3, $var4) = explode('/', $url);	
	
	$idioma = $var1;
	if($idioma=="") $idioma="br";

?>

<?php if($var1 === 'admin') {
    include_once(__DIR__.'/admin/index.php'); 
    die();
}?>

<?php 
	$language = $idioma;

	if ($language == 'br') $language = 'pt';

	$conn = makeConnection();
	$statement = $conn->prepare('SELECT id FROM business_units WHERE parent_id IS NULL');
	$statement->execute();

	$businessUnits = [];
	$rootBusinessUnits = [];

	foreach ($statement->fetchAll(PDO::FETCH_ASSOC) as $i => $j) {     
		$businessUnit = new BusinessUnit($j['id'], $language, TRUE, TRUE, TRUE, TRUE);
		array_push($businessUnits, $businessUnit->toJSON(FALSE));
		array_push($rootBusinessUnits, $businessUnit->name_getter());
	};

	terminateConnection();

?>

<?php

	$conn = conecta();

	header("Content-type: text/html; charset=".$charset,true);
	setlocale(LC_ALL, "pt_BR");
	setlocale(LC_ALL, "pt_BR");
	setlocale(LC_MONETARY,"pt_BR", "ptb");
	session_start();
	$script="";

	//include("funcoes.php");

	$sql = "select * from config where id = :id";
	$sql = $conn->prepare($sql);
	$sql->bindValue(':id', '1', PDO::PARAM_INT);
	$sql->execute();

	if($sql->rowCount()==0){
		echo "Nenhum arquivo encontrado";
	}else{
		$rs = $sql->fetch(PDO::FETCH_ASSOC);
		$nome_site=$rs['nome'];
		$email_site=$rs['email'];
		$host=$rs['url_site'];
		$host_adm=$host."/adm";
		$title_site=$rs['title'];
		$description_site=$rs['description'];
		$keywords_site=$rs['keywords'];
	}

	$diretorio="img_up/"; 



	//BR
	if ($idioma=="br") {
		$m_inicio="INÍCIO";
		$m_quem_somos="QUEM SOMOS";
		$m_servicos="SERVIÇOS";
		$m_socios="SÓCIOS";
		$m_equipe="ESCOPO DE ATUAÇÃO";
		$m_contato="CONTATO";

		$quem="QUEM";
		$somos="SOMOS";
		
		$dire="DIRE";
		$trizes="TRIZES";
		$missao="MISSÃO";
		$visao="VISÃO";
		$valores="VALORES";

		$ser="SER";
		$vicos="VIÇOS";

		$socios="SÓCIOS";
		$fundadores="FUNDADORES";

		$e="Escopo ";
		$quipe="de Atuação";

		$seu_nome="Seu Nome";
		$telefone="Telefone";
		$e_mail="E-mail";
		$mensagem="Escreva aqui sua mensagem";
		$enviar="ENVIAR";

		$assinar="ASSINAR";
		$newsletter="NEWSLETTER";

		$logo="logo.png";
		$slide1="slide_1.jpg";
		$slide2="slide_2.jpg";
		$slide3="slide_3.jpg";
		$f_det1="f_det1.jpg";
		$f_det2="f_det2.jpg";
		$f_det4="f_det4.jpg";
		$texto_fim_servicos="texto_fim_servicos.jpg";
		$texto_equipe="texto_equipe.jpg";
		$faixa_azul="faixa_azul.png";
		$copy="Copyright © 2020 TEKMA - Todos os direitos reservados";

		$txt_premissa1="Com mais de 30 anos de experiência em projetos de engenharia ligados à mobilidade para grandes empresas, principalmente do setor automotivo, os profissionais da <b>TEKMA</b> tem expertise no desenvolvimento de produtos de excelência em inovação e tecnologia adequados para competir no acirrado mercado global.<br><br>";
		$txt_premissa2="Com habilidades específicas adquiridas ao longo de suas carreiras, a equipe <b>TEKMA</b> consegue compreender as necessidades dos clientes e prover soluções completas em serviços de engenharia para as mais variadas demandas da mobilidade.<br><br>";
        $txt_premissa3="Organizada em áreas distintas de atuação, facilitando a entrada e o entendimento das demandas dos clientes, a <b>TEKMA</b>, pelo seu modelo de negócio, promove a integração entre os seus especialistas de forma ágil e fácil para proporcionar os melhores resultados aos seus clientes.<br/><br/>";
        $txt_premissa4="A <b>TEKMA</b>, dentro dos seus valores, entende que a confidencialidade<b>¹</b> é a garantia do resguardo das informações dadas pessoalmente em confiança e a proteção contra a sua revelação não autorizada e, desta forma, se compromete a manter em sigilo todas as informações que serão trocadas durante a elaboração ou a execução de determinado projeto, desenvolvido em conjunto com seus clientes e parceiros.<br/><br/>";
        $txt_premissa5="1. O termo de confidencialidade é regido, principalmente, pelo Código Civil (Lei Federal nº 10.406, 10 de janeiro de 2002), em sua parte sobre o Direito das Obrigações.<br/><br/>";


		$txt_conceito1="A <b>TEKMA</b> nasce no modelo de negócios de cooperativa de trabalho para a prestação de serviços de engenharia e correlatos, oferecendo especialistas nas principais áreas de conhecimento.";
		$txt_conceito2="A identidade de propósitos e interesses assegura que os desenvolvimentos de projetos, protótipos, avaliações de conjuntos de componentes e veículos sejam realizados com a máxima eficácia técnica e com resultados de altíssima qualidade.";
		$txt_conceito3="Além destas competências, a <b>TEKMA</b> oferece gestão de projetos nas áreas de pós-vendas, logística, qualidade e processos.";

		$txt_missao="Nossa missão é prover soluções em serviços da mobilidade de forma avançada, precisa e efetiva agregando valores pautados em competitividade, segurança e sustentabilidade.";
		$txt_visao="A <b>TEKMA</b> aspira ser reconhecida com distinção como uma prestadora de serviços de engenharia ligados à mobilidade.";
		$txt_valores="• Competência • Confiança • Eficiência • Ética • Excelência • Honestidade • Inovação • Qualidade • Responsabilidade social • Confidencialidade • Sustentabilidade.";

		$txt_serv1="Consultoria e assessoria no gerenciamento de projetos de engenharia de produtos com o uso de ferramentas da qualidade.";
		$txt_serv2="Gerenciamento e execução de testes funcionais em parceria com o cliente ou empresas especializadas.";
		$txt_serv3="Gerenciamento e assessoria em engenharia de serviços, treinamentos e literaturas técnicas no pós-venda.";
		$txt_serv4="Gerenciamento e assessoria em testes de durabilidade, análises de falhas e de confiabilidade do produto.";
		$txt_serv5="Treinamento em sistemas e ferramentas da qualidade e melhoria contínua.";
		$txt_serv6="Consultoria e assessoria em conceituação de produtos e processos. Desde a elaboração dos cadernos de conceitos e encargos até a execução e acompanhamento.";
	}

	//EN
	if($idioma=="en") {
		$m_inicio="HOME";
		$m_quem_somos="WHO  WE ARE?";
		$m_servicos="SERVICES";
		$m_socios="PARTNERS";
		$m_equipe="SCOPE OF OPERATION";
		$m_contato="CONTACT";

		$quem="WHO";
		$somos=" WE ARE?";
		
		$dire="GUIDE";
		$trizes="LINES";
		$missao="MISSION";
		$visao="VISION";
		$valores="VALUES";

		$ser="SER";
		$vicos="VICES";

		$socios="FOUNDING";
		$fundadores="PARTNERS";

		$e="Scope ";
		$quipe="of Operation";

		$seu_nome="Your name";
		$telefone="Phone";
		$e_mail="E-mail";
		$mensagem="Your message";
		$enviar="SUBMIT";

		$assinar="SUBSCRIBE";
		$newsletter="NEWSLETTER";

		$logo="logo_en.png";
		$slide1="slide_1_en.jpg";
		$slide2="slide_2_en.jpg";
		$slide3="slide_3.jpg";
		$f_det1="f_det1_en.jpg";
		$f_det2="f_det2_en.jpg";
		$f_det4="f_det4.jpg";
		$texto_fim_servicos="texto_fim_servicos_en.jpg";
		$texto_equipe="texto_equipe_en.jpg";
		$faixa_azul="faixa_azul_en.jpg";
		$copy="Copyright © 2020 TEKMA -  All rights reserved";

		$txt_premissa1="With more than 30 years of experience in mobility-related engineering projects for large companies, mainly in the automotive sector, TEKMA professionals have expertise in developing products of excellence in innovation and appropriate technology to compete in the fierce global market.<br><br>";
		$txt_premissa2="With specific skills acquired throughout their careers, the TEKMA team can understand customer needs and provide complete solutions in engineering services for the most varied mobility demands.<br><br>";
        $txt_premissa3="Organized in different areas of operation, facilitating the entry and understanding of customer demands, <b>TEKMA</b>, due to its business model, promotes the integration among its specialists in an agile and easy way to provide the best results to your customers.<br/><br/>";
        $txt_premissa4="<b> TEKMA </b>, within its values, understands that confidentiality<b>¹</b> is the guarantee of safeguarding information personally given in confidence and protection against its disclosure not authorized and, thus, undertakes to keep confidential all information that is exchanged during the preparation or execution of a given project, developed jointly with its customers and partners.<br/><br/>";
        $txt_premissa5="1. The term of confidentiality is governed mainly by the Brazilian Civil Code (Federal Law No.10,406 from January 10, 2002), in its part on the Law of Obligations.<br/><br/>";

		$txt_conceito1="<b>TEKMA</b> is born in the business model of cooperative work for the provision of engineering and related services, offering specialists in the main areas of knowledge.";
		$txt_conceito2="The identity of purposes and interests ensures that the development of projects, prototypes, evaluations of sets of components and vehicles are carried out with maximum technical efficacy and with results of very high quality.";
		$txt_conceito3="In addition to these competencies, TEKMA offers project management in the areas of after sales, logistics, quality and processes.";

		$txt_missao="Our mission is to provide solutions in mobility services in an advanced, accurate and effective way by adding values based on competitiveness, security and sustainability";
		$txt_visao="<b>TEKMA</b> aspires to be recognized with distinction as a mobility-related engineering service provider.";
		$txt_valores="• Competence • Trust • Efficiency • Ethics • Excellence • Honesty • Innovation • Quality • Social Responsibility • Confidentiality • Sustainability.";

		$txt_serv1="Consulting and advice in the management of product engineering projects with the use of quality tools.";
		$txt_serv2="Management and execution of functional tests in partnership with the client or specialized companies.";
		$txt_serv3="Management and advisory in engineering services, training and technical literature in the after-sales.";
		$txt_serv4="Management and advisory in endurance tests, failure analysis and product reliability.";
		$txt_serv5="Training in systems and tools of quality and continuous improvement.";
		$txt_serv6="Consulting and advisory in conceptualization of products and processes. From the elaboration of the concept notebooks and specifications to the execution and monitoring";
                   
	}

	if ($og_type == "") {
		$og_type = "website";
	}
	if ($img_site == "") {
		$img_site = $host."/img_site/logo.png";
	}
	if ($zoom_site == "") {
		$zoom_site = 14;
	}	
	if ($og_type == "") {
		$og_type = "website";
	}
	if ($img_site == "") {
		$img_site = $host."/img_site/logo.png";
	}
	if ($temp_title_site <> "") {
		$title_site  =$temp_title_site;
	}
	if ($temp_description_site <> "") {
		$description_site = $temp_description_site;
	}
	if ($temp_keywords_site <>" ") {
		$keywords_site = $temp_keywords_site;
	}

	echo "<!-- Var 1=$var1 / Var2=$var2 / Var3=$var3 -->";
		
	?>

<!DOCTYPE html>

<html prefix="og: https://ogp.me/ns#" lang="pt-br">

	<head>
		<title><?php echo $title_site;?></title>
		<base href="<?php echo $host."/".$url;?>" />
		<meta charset="<?php echo $charset;?>">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $charset;?>">
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
		<link rel="stylesheet" href="<?php echo $host;?>/estilo.css?v4" type="text/css" media="screen">
		<link rel="stylesheet" href="<?php echo $host;?>/css/hexselector.css" type="text/css" media="screen">
		<link rel="stylesheet" href="<?php echo $host;?>/css/estilo.css" type="text/css" media="screen">
		<link href="https://fonts.googleapis.com/css?family=Oswald:600&display=swap" rel="stylesheet">
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
					
					<a class="navbar-brand" href="<?php echo $host;?>"><img src="img_site/<?php echo $logo;?>" width="250" height="71" border="0" alt=""></a>
					
				</div>

				<div id="navbar" class="navbar-collapse collapse">
				
					<ul class="nav navbar-nav navbar-right">
				
						<div class='text-right bg_bandeira'>
							<a href='<?php echo $host;?>/br'><img src="<?php echo $host;?>/img_site/br.png" border="0" alt="" style='padding:3px;'/></a>
							<a href='<?php echo $host;?>/en'><img src="<?php echo $host;?>/img_site/en.png" border="0" alt="" style='padding:3px;'/></a>
						</div>
				
						<li><a href='#inicio' class="scrollto"><?php echo $m_inicio;?></a></li>
						<li><a href='#quem-somos' class="scrollto"><?php echo $m_quem_somos;?></a></li>
						<li><a href='#servicos' class="scrollto"><?php echo $m_servicos;?></a></li>
						<li><a href='#socios' class="scrollto"><?php echo $m_socios;?></a></li>
						<li><a href='#equipe' class="scrollto"><?php echo $m_equipe;?></a></li>
						<li><a href='#contato' class="scrollto"><?php echo $m_contato;?></a></li>
					
					</ul>

				</div>

			</div>
		</nav>

		<div class='clear'></div>
	
		<section id='inicio' class='bg_conteudo'>
			<section id='slide'>
				<div id='carousel_1' class='carousel slide carousel-fade center'>
					<div class='carousel-inner'>
						<div class='item carousel-full active center' style='background-image: url(img_site/<?php echo $slide1;?>);'>
							<div class='clear'></div>
						</div>
						<div class='item carousel-full center' style='background-image: url(img_site/<?php echo $slide2;?>);'>
							<div class='clear'></div>
						</div>
						<div class='item carousel-full center' style='background-image: url(img_site/<?php echo $slide3;?>);'>
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

			<div class="container">
				<p class='topico text-right'><span class='laranja'><?php echo $quem;?></span><?php echo $somos;?></p>
				<div class='col-sm-4'><?php echo "<p class='conceito'>$txt_conceito1</p>";?></div>
				<div class='col-sm-4'><?php echo "<p class='conceito'>$txt_conceito2</p>";?></div>
				<div class='col-sm-4'><?php echo "<p class='conceito'>$txt_conceito3</p>";?></div>
			</div>	
			<br>

			<img src="img_site/<?php echo $f_det1;?>"  width='100%'>
			<br>	

			<div class="container">

				<p class='topico text-right'><span class='laranja'><?php echo $dire;?></span><?php echo $trizes;?></p>

				<div class='col-sm-8'>
					<p class='titulo'><?php echo $missao;?></p>
					<?php echo $txt_missao;?>
				</div>

				<div class='col-sm-4'></div>
				<div class='clear'></div>
				<br>

				<div class='col-sm-8'>
					<p class='titulo'><?php echo $visao;?></p>
					<?php echo $txt_visao;?>
				</div>

				<div class='col-sm-4'></div>
				<div class='clear'></div>
				<br>

				<div class='col-sm-8'>
					<p class='titulo'><?php echo $valores;?></p>
					<?php echo $txt_valores;?>
					<br />
					<a href="/downloads/codigo_de_etica_tekma_2021_v2.pdf" download>Clique aqui para fazer o download do nosso código de ética</a>
				</div>

				<div class='col-sm-4'></div>
				<div class='clear'></div>


				<br/>

			</div>

			<div class='clear'></div>
			<img src="img_site/<?php echo $f_det2;?>" width='100%'/>

			<br/><br/>

		</section>

		<div class='clear'></div>

		<section id='servicos' class='bg_conteudo'>

			<div class="container">

				<p class='topico text-right'><span class='laranja'><?php echo $ser;?></span><?php echo $vicos?></p>

				<div class='col-sm-4 serv'>
					<span class='nro'>1</span>
					<?php echo "<p class='serv'>$txt_serv1</p>";?>
					<div class='clear'></div>
				</div>

				<div class='col-sm-4 borda serv'>
					<div class='nro'>2</div>
					<?php echo $txt_serv2;?>
					<div class='clear'></div>
				</div>

				<div class='col-sm-4 serv'>
					<div class='nro'>3</div>
					<?php echo $txt_serv3;?>
					<div class='clear'></div>
				</div>

			</div>

			<br><br>

			<div class="parallax bg1" data-divisor="2">
				<div class="alt_400"></div>
			</div>

			<br><br>

			<div class="container">

				<div class='col-sm-4 serv'>
					<div class='nro'>4</div>
					<?php echo $txt_serv4;?>
					<div class='clear'></div>
				</div>

				<div class='col-sm-4 borda serv'>
					<div class='nro'>5</div>
					<?php echo $txt_serv5;?>
					<div class='clear'></div>
				</div>

				<div class='col-sm-4 serv'>
					<div class='nro'>6</div>
					<?php echo $txt_serv6;?>
					<div class='clear'></div>
				</div>

			</div>

			<div class='clear'></div>
			<br><br>

			<img src="img_site/<?php echo $f_det4;?>" width='100%'>
			<div class='clear'></div>
			<img src="img_site/<?php echo $texto_fim_servicos;?>" class='img-responsive center'>

		</section>

		<div class='clear'></div>
		<br><br>
		
		<section id='socios' class='bg_conteudo'>
			<div class="container">

				<p class='topico text-right'><span class='laranja'><?php echo $socios;?></span> <?php echo $fundadores;?></p>

				<div class='col-sm-12 mp0 mtop10'>
					<img src="img_site/mosaico_equipe.jpg" class='img-responsive'><br>
					<div class='clear'></div>
					<img src="img_site/<?php echo $texto_equipe;?>" class='img-responsive' style='max-width:100%;float:right;'>
					
					<br><br><br><br>
				</div>

			</div>
		</section>

		<div class='clear'></div>

		<section id='equipe' class='bg_conteudo'>

			<div class="container">
				<p class='topico text-right'><span class='laranja'><?php echo $e;?></span><?php echo $quipe;?></p>
				<div class='col-sm-6 text-justify'><?php echo $txt_premissa1;?></div>
				<div class='col-sm-6 text-justify'><?php echo $txt_premissa2;?></div>
                <div class='clear'></div>
                <div class='col-sm-12 text-justify'><?php echo $txt_premissa3;?></div>
                <div class='col-sm-12 text-justify'><?php echo $txt_premissa4;?></div>
                <div class='col-sm-12 text-justify' style='font-size: .8em; font-weight: 700;'><?php echo $txt_premissa5;?></div>
			</div>

			<br/><br/>

			<section id="root" class="container-fluid"></section>
			
			<br/><br/>

			<img src="img_site/<?php echo $faixa_azul;?>" width='100%'>
			<br><br>

		</section>

		<div class='clear'></div>
		<br><br>

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
						<input type='text' class='form-control inputs' name='nome' id='nome' value='' placeholder='<?php echo $seu_nome;?>' required><br>
					</div>

					<div class='col-sm-4'>
						<input type='text' class='form-control inputs' name='telefones' id='telefone' value='' placeholder='<?php echo $telefone;?>'><br>
					</div>

					<div class='col-sm-4'>
						<input type='email' class='form-control inputs' name='email' id='email' value='' placeholder='<?php echo $e_mail;?>' required><br>
					</div>

					<div class='col-sm-12'>
						<textarea class='form-control inputs' name='mensagem' id='mensagem' wrap='virtual' style='height:265px;' placeholder='<?php echo $mensagem;?>' required></textarea>
					</div>

					<div class='col-sm-6'></div>

					<div class='col-sm-6'>
						<div id='div_form'></div>	

						<p class='text-right'><br>
							<button type='submit' name='contato' value='ok' id='contato' class='btn btn-site botao'><b><?php echo $enviar;?></b></button>
						</p>
					</div>

				</form>
				<br><br>
			</div>
		</section>
		
		<footer>
			<div class="container">
				
				<div class='col-sm-8'><br>
					<img src="img_site/logo_rodape.png" width="250"><br>
					<span class='copy'><?php echo $copy;?></span>
				</div>

				<div class='col-sm-4 text-right' style='padding-top:65px'>
					<a class="hidden-xs hidden-sm hidden-md" href="https://web.whatsapp.com/send?phone=5511976204799&text=&source&data&app_absent" target="_blank"><img src="img_site/logo_wpp.png" height="62" border="0" alt=""></a>
					<a class="hidden-lg" href="https://api.whatsapp.com/send?phone=5511976204799&text=" target="_blank"><img src="img_site/logo_wpp.png" height="62" border="0" alt=""></a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="https://www.instagram.com/tekma.mobilidade" target="_blank"><img src="img_site/instagram.png" height="62" border="0" alt=""></a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="https://www.facebook.com/tekma.mobilidade" target="_blank"><img src="img_site/face.png" height="62" border="0" alt=""></a>
					&nbsp;&nbsp;&nbsp;&nbsp;
					<a href="https://www.linkedin.com/company/tekma-mobilidade" target="_blank"><img src="img_site/linkedin_2.png" height="62" border="0" alt=""></a>
				</div>
			
				<br><br>
			</div>
		</footer>

		<script src="<?php echo $host;?>/js/jquery.js"></script>
		<script src="<?php echo $host;?>/js/bootstrap.js"></script>
		<script src="<?php echo $host;?>/js/scrollto.js"></script>
		<script src="<?php echo $host;?>/js/parallax.js"></script>
		<script type='text/javascript'>
			var host = "<?php echo $host;?>/";
			$(document).ready(function(){<?php echo $script;?>});
		</script>

		<script>

			/* ScrollTo Links */
			$('a.scrollto').click(function(e){
				e.preventDefault();
				//y=altura da diferença, um tipo de margem entre o div e o topo ou bottom, o que normalmente poderá ser a altura do menu (negativo)
				$('html,body').scrollTo(this.hash, this.hash, {gap:{y:-90}});
				if ($('.navbar-collapse').hasClass('in')){
					$('.navbar-collapse').removeClass('in').addClass('collapse');
				}
			});

			/* toTop */
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

			/* Form Contato */
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
				return false;
			});

			/* Form Newsletter */
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
					alert("Falha no envio"); 
				});
				
				return false;
			});

		</script>

		<script type="text/javascript" src="js/Router.js"></script>

		<script>
            let businessUnits = JSON.parse(JSON.stringify(<?php echo json_encode($businessUnits); ?>));
            let router = new HistoryStack(businessUnits, '<?php echo $language; ?>');
            router.navigate();
        </script>

		<!-- <script src="<?php echo $host;?>/scripts.js"></script> -->

	</body>
</html>