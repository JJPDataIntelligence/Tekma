<?php 
	require("connection.php");
	function readcooperados ($english=FALSE) {
		
		$conn = makeconnection();

		$sql = "SELECT * FROM cooperative_partners WHERE active = 1";

		try {
			$stmt = $conn->query($sql);
			$i = 0;
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$i++;
				$image = "<div class='col-sm-5 image'><img src='/".((substr($row['imagem'], 0, 9) === "img_site/") ? $row['imagem'] : "img_site/".$row['imagem'])."' class='img-responsive'></div>";
				$nome = "<p class='equipe_nome'>".str_ireplace($row['apelido'], "<span class='equipe_laranja'>".$row['apelido']."</span>", $row['nome'])."</p>";
				$email = $row['email'] <> "" ? "<p class='equipe_email'><a class='equipe_email' href='mailto:".$row['email']."'>".$row['email']."</a></p>" : "";
				$description = $english ? "<div class='equipe_texto'>".$row['descricao_en']."</div>" : "<div class='equipe_texto'>".$row['descricao_pt']."</div>";
				$linkedin = $row['linkedin'] <> "" ? "<p class='text-right'><a href='".$row['linkedin']."' target='_blank'><img src='img_site/linkedin.png'></a></p>" : "";
				$linecleanup = $i % 2 == 0 ? "<div class='clear'></div><br/><br/>" : "";

				echo "
					<div class='col-sm-6 mp0 mtop10'>
						<div class='conteudo'>
							$image
							<div class='col-sm-7'>
								$nome
								$email
								$description
								$linkedin
							</div>
							<div class='clear'></div>
						</div>
						<div class='clear'></div>
					</div>
					$linecleanup
				";

			}
		} catch (PDOException $e){
			echo $e;
		}

		terminateconnection();
	}

?>