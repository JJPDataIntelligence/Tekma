<?php require_once(__DIR__."/../model/User.php");?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php require_once(__DIR__.'/../vendor/shuchkin/simplexlsx/src/SimpleXLSX.php'); ?>


<?php if (!isset($_SESSION)) session_start();?>
<?php 
    if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
    }
?>

<?php function remove_accents($string): string {
    if ( !preg_match('/[\x80-\xff]/', $string) )
        return $string;

    $chars = array(
        // Decompositions for Latin-1 Supplement
        chr(195).chr(128) => 'A', chr(195).chr(129) => 'A',
        chr(195).chr(130) => 'A', chr(195).chr(131) => 'A',
        chr(195).chr(132) => 'A', chr(195).chr(133) => 'A',
        chr(195).chr(135) => 'C', chr(195).chr(136) => 'E',
        chr(195).chr(137) => 'E', chr(195).chr(138) => 'E',
        chr(195).chr(139) => 'E', chr(195).chr(140) => 'I',
        chr(195).chr(141) => 'I', chr(195).chr(142) => 'I',
        chr(195).chr(143) => 'I', chr(195).chr(145) => 'N',
        chr(195).chr(146) => 'O', chr(195).chr(147) => 'O',
        chr(195).chr(148) => 'O', chr(195).chr(149) => 'O',
        chr(195).chr(150) => 'O', chr(195).chr(153) => 'U',
        chr(195).chr(154) => 'U', chr(195).chr(155) => 'U',
        chr(195).chr(156) => 'U', chr(195).chr(157) => 'Y',
        chr(195).chr(159) => 's', chr(195).chr(160) => 'a',
        chr(195).chr(161) => 'a', chr(195).chr(162) => 'a',
        chr(195).chr(163) => 'a', chr(195).chr(164) => 'a',
        chr(195).chr(165) => 'a', chr(195).chr(167) => 'c',
        chr(195).chr(168) => 'e', chr(195).chr(169) => 'e',
        chr(195).chr(170) => 'e', chr(195).chr(171) => 'e',
        chr(195).chr(172) => 'i', chr(195).chr(173) => 'i',
        chr(195).chr(174) => 'i', chr(195).chr(175) => 'i',
        chr(195).chr(177) => 'n', chr(195).chr(178) => 'o',
        chr(195).chr(179) => 'o', chr(195).chr(180) => 'o',
        chr(195).chr(181) => 'o', chr(195).chr(182) => 'o',
        chr(195).chr(182) => 'o', chr(195).chr(185) => 'u',
        chr(195).chr(186) => 'u', chr(195).chr(187) => 'u',
        chr(195).chr(188) => 'u', chr(195).chr(189) => 'y',
        chr(195).chr(191) => 'y',
        // Decompositions for Latin Extended-A
        chr(196).chr(128) => 'A', chr(196).chr(129) => 'a',
        chr(196).chr(130) => 'A', chr(196).chr(131) => 'a',
        chr(196).chr(132) => 'A', chr(196).chr(133) => 'a',
        chr(196).chr(134) => 'C', chr(196).chr(135) => 'c',
        chr(196).chr(136) => 'C', chr(196).chr(137) => 'c',
        chr(196).chr(138) => 'C', chr(196).chr(139) => 'c',
        chr(196).chr(140) => 'C', chr(196).chr(141) => 'c',
        chr(196).chr(142) => 'D', chr(196).chr(143) => 'd',
        chr(196).chr(144) => 'D', chr(196).chr(145) => 'd',
        chr(196).chr(146) => 'E', chr(196).chr(147) => 'e',
        chr(196).chr(148) => 'E', chr(196).chr(149) => 'e',
        chr(196).chr(150) => 'E', chr(196).chr(151) => 'e',
        chr(196).chr(152) => 'E', chr(196).chr(153) => 'e',
        chr(196).chr(154) => 'E', chr(196).chr(155) => 'e',
        chr(196).chr(156) => 'G', chr(196).chr(157) => 'g',
        chr(196).chr(158) => 'G', chr(196).chr(159) => 'g',
        chr(196).chr(160) => 'G', chr(196).chr(161) => 'g',
        chr(196).chr(162) => 'G', chr(196).chr(163) => 'g',
        chr(196).chr(164) => 'H', chr(196).chr(165) => 'h',
        chr(196).chr(166) => 'H', chr(196).chr(167) => 'h',
        chr(196).chr(168) => 'I', chr(196).chr(169) => 'i',
        chr(196).chr(170) => 'I', chr(196).chr(171) => 'i',
        chr(196).chr(172) => 'I', chr(196).chr(173) => 'i',
        chr(196).chr(174) => 'I', chr(196).chr(175) => 'i',
        chr(196).chr(176) => 'I', chr(196).chr(177) => 'i',
        chr(196).chr(178) => 'IJ',chr(196).chr(179) => 'ij',
        chr(196).chr(180) => 'J', chr(196).chr(181) => 'j',
        chr(196).chr(182) => 'K', chr(196).chr(183) => 'k',
        chr(196).chr(184) => 'k', chr(196).chr(185) => 'L',
        chr(196).chr(186) => 'l', chr(196).chr(187) => 'L',
        chr(196).chr(188) => 'l', chr(196).chr(189) => 'L',
        chr(196).chr(190) => 'l', chr(196).chr(191) => 'L',
        chr(197).chr(128) => 'l', chr(197).chr(129) => 'L',
        chr(197).chr(130) => 'l', chr(197).chr(131) => 'N',
        chr(197).chr(132) => 'n', chr(197).chr(133) => 'N',
        chr(197).chr(134) => 'n', chr(197).chr(135) => 'N',
        chr(197).chr(136) => 'n', chr(197).chr(137) => 'N',
        chr(197).chr(138) => 'n', chr(197).chr(139) => 'N',
        chr(197).chr(140) => 'O', chr(197).chr(141) => 'o',
        chr(197).chr(142) => 'O', chr(197).chr(143) => 'o',
        chr(197).chr(144) => 'O', chr(197).chr(145) => 'o',
        chr(197).chr(146) => 'OE',chr(197).chr(147) => 'oe',
        chr(197).chr(148) => 'R',chr(197).chr(149) => 'r',
        chr(197).chr(150) => 'R',chr(197).chr(151) => 'r',
        chr(197).chr(152) => 'R',chr(197).chr(153) => 'r',
        chr(197).chr(154) => 'S',chr(197).chr(155) => 's',
        chr(197).chr(156) => 'S',chr(197).chr(157) => 's',
        chr(197).chr(158) => 'S',chr(197).chr(159) => 's',
        chr(197).chr(160) => 'S', chr(197).chr(161) => 's',
        chr(197).chr(162) => 'T', chr(197).chr(163) => 't',
        chr(197).chr(164) => 'T', chr(197).chr(165) => 't',
        chr(197).chr(166) => 'T', chr(197).chr(167) => 't',
        chr(197).chr(168) => 'U', chr(197).chr(169) => 'u',
        chr(197).chr(170) => 'U', chr(197).chr(171) => 'u',
        chr(197).chr(172) => 'U', chr(197).chr(173) => 'u',
        chr(197).chr(174) => 'U', chr(197).chr(175) => 'u',
        chr(197).chr(176) => 'U', chr(197).chr(177) => 'u',
        chr(197).chr(178) => 'U', chr(197).chr(179) => 'u',
        chr(197).chr(180) => 'W', chr(197).chr(181) => 'w',
        chr(197).chr(182) => 'Y', chr(197).chr(183) => 'y',
        chr(197).chr(184) => 'Y', chr(197).chr(185) => 'Z',
        chr(197).chr(186) => 'z', chr(197).chr(187) => 'Z',
        chr(197).chr(188) => 'z', chr(197).chr(189) => 'Z',
        chr(197).chr(190) => 'z', chr(197).chr(191) => 's'
    );

    $string = strtr($string, $chars);

    return $string;
}?>

<?php function applyImageAlphaMask( &$picture, $mask ) {
    // Get sizes and set up new picture
    $xSize = imagesx( $picture );
    $ySize = imagesy( $picture );
    $newPicture = imagecreatetruecolor( $xSize, $ySize );
    imagesavealpha( $newPicture, true );
    imagefill( $newPicture, 0, 0, imagecolorallocatealpha( $newPicture, 0, 0, 0, 127 ) );

    // Resize mask if necessary
    if( $xSize != imagesx( $mask ) || $ySize != imagesy( $mask ) ) {
        $tempPic = imagecreatetruecolor( $xSize, $ySize );
        imagecopyresampled( $tempPic, $mask, 0, 0, 0, 0, $xSize, $ySize, imagesx( $mask ), imagesy( $mask ) );
        imagedestroy( $mask );
        $mask = $tempPic;
    }

    // Perform pixel-based alpha map application
    for( $x = 0; $x < $xSize; $x++ ) {
        for( $y = 0; $y < $ySize; $y++ ) {
            $alpha = imagecolorsforindex( $mask, imagecolorat( $mask, $x, $y ) );
            $alpha = 127 - floor( $alpha[ 'red' ] / 2 );
            $color = imagecolorsforindex( $picture, imagecolorat( $picture, $x, $y ) );
            imagesetpixel( $newPicture, $x, $y, imagecolorallocatealpha( $newPicture, $color[ 'red' ], $color[ 'green' ], $color[ 'blue' ], $alpha ) );
        }
    }

    // Copy back to original picture
    imagedestroy( $picture );
    $picture = $newPicture;
}?>

<?php function overlayPNG(&$baseImage, $overlayImage) {
    $width = imagesx($baseImage);
    $height = imagesy($baseImage);
    $newwidth = imagesx($overlayImage);
    $newheight = imagesy($overlayImage);

    $outImage = imagecreatetruecolor($newwidth, $newheight);

    imagecopyresampled($outImage, $baseImage, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    imagecopyresampled($outImage, $overlayImage, 0, 0, 0, 0, $newwidth, $newheight, $newwidth, $newheight);

    // Copy back to original picture
    imagedestroy( $baseImage );
    $baseImage = $outImage;
}?>

<?php 
    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        switch ($_GET["action"]) {
            case "activate":
                
                if (isset($_GET['id'])) {
                    try {
                        Member::toggleMemberState($_GET['id'], 'activate');
                        $_SESSION["actionSuccess"] = "Perfil Ativado Com Sucesso !";
                        header("Location: ".$_SERVER["HTTP_REFERER"]);
                        die();
                    } catch (Exception $e) {
                        $_SESSION["actionError"] = $e->getMessage();
                        header("Location: ".$_SERVER["HTTP_REFERER"]);
                        die();
                    }
                } else {
                    try {
                        $user->activateProfile(true);
                        $_SESSION["actionSuccess"] = "Perfil Ativado Com Sucesso !";
                        $_SESSION["user"] = serialize($user);
                        header("Location: /admin");
                        die();
                    } catch (Exception $e) {
                        $_SESSION["actionError"] = $e->getMessage();
                        header("Location: /admin");
                        die();
                    }
                }
                
                break;

            case "deactivate":
                if (isset($_GET['id'])) {
                    try {
                        Member::toggleMemberState($_GET['id'], 'deactivate');
                        $_SESSION["actionSuccess"] = "Perfil Desativado Com Sucesso !";
                        header("Location: ".$_SERVER["HTTP_REFERER"]);
                        die();
                    } catch (Exception $e) {
                        $_SESSION["actionError"] = $e->getMessage();
                        header("Location: ".$_SERVER["HTTP_REFERER"]);
                        die();
                    }
                } else {
                    try {
                        $user->activateProfile(false);
                        $_SESSION["actionSuccess"] = "Perfil Desativado Com Sucesso !";
                        $_SESSION["user"] = serialize($user);
                        header("Location: /admin");
                        die();
                    } catch (Exception $e) {
                        $_SESSION["actionError"] = $e->getMessage();
                        header("Location: /admin");
                        die();
                    }
                }

                break;
            case "release":
                try {
                    if(isset($_GET["user"])) {
                        $owner =  new User($_GET["user"]);
                        $owner->releaseProfile();
                        if ($owner->id_getter() == $user->id_getter()) {
                            $_SESSION['user'] = serialize($owner);
                        }
                        $_SESSION["actionSuccess"] = "Perfil Desvinculado Com Sucesso !";
                        header("Location: ".$_SERVER["HTTP_REFERER"]);
                    } else {
                        $user->releaseProfile();
                        $_SESSION["user"] = serialize($user);
                        $_SESSION["actionSuccess"] = "Perfil Desvinculado Com Sucesso !";
                        header("Location: /admin");
                    }
                    die();
                } catch (Exception $e) {
                    $_SESSION["actionError"] = $e->getMessage();
                    $_SESSION["user"] = serialize($user);
                    header("Location: /admin");
                    die();
                }
                break;
        }
        
        
    } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        switch ($_POST["action"]) {
            
            case 'searchProfiles':
                if (!isset($_POST["searchTerm"])) {
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => "Deve Conter um Termo de Busca"));
                    die();
                }
                
                try {
                    $profiles = Member::findMembers($_POST["searchTerm"], $_POST["limit"] ? $_POST["limit"] : null, $_POST["offset"] ? $_POST["offset"] : null);
                    echo json_encode(array("success" => true, "profiles" => $profiles));
                    die();
                } catch (Exception $e) {                
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    die();
                }
                break;

            case 'deactivateProfile':
                $profileID = $_POST["id"];
            
                try {
                    Member::toggleMemberState($profileID, "deactivate");
                    echo json_encode(array("success" => true, "message" => "Perfil Desativado com Sucesso !"));
                    die();
                } catch (Exception $e) {                
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => $e->getMessage(), "profileID" => $profileID));
                    die();
                }
                break;
            
            case 'activateProfile':
                $profileID = $_POST["id"];
            
                try {
                    Member::toggleMemberState($profileID, "activate");
                    echo json_encode(array("success" => true, "message" => "Perfil Ativado com Sucesso !"));
                    die();
                } catch (Exception $e) {                
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => $e->getMessage(), "profileID" => $profileID));
                    die();
                }
                break;

            case 'createProfile': 
                if (!isset($_POST["fullName"]) ||
                    !isset($_POST["nickname"]) ||
                    !isset($_POST["email"]) ||
                    !isset($_POST["shortDescription"]) ||
                    !isset($_POST["description"])
                ){
                    $_SESSION["createError"] = "Informações Obrigatórias Não Foram Encontradas";
                    header('Location: /admin/profiles');
                    die();
                }
                
                $fullName = mb_strtoupper($_POST["fullName"], "utf-8");
                $nickname = mb_strtoupper($_POST["nickname"], "utf-8");
                $title = isset($_POST['title']) ? $_POST['title'] : null;
                $email = $_POST["email"];
                $linkedin = isset($_POST['linkedin']) ? $_POST['linkedin'] : null;
                $shortDescription = $_POST["shortDescription"];
                $description = $_POST["description"];

                // Create Profile & DB Record
                try {
                    $profileID = Member::createMember($fullName, $nickname, $title,
                        null, $linkedin, $email, $description, null,
                        $shortDescription, null, null
                    );
                } catch (Exception $e) {
                    $_SESSION["createError"] = $e->getMessage();
                    header('Location: /admin/profiles');
                    die();
                }              

                header('Location: /admin/profiles');
                break;

    
            case 'removeProfile': 
                $userID = $_POST["id"];
            
                try {
                    Member::removeMember($userID);
                    echo json_encode(array("success" => true, "message" => "Perfil Removido com Sucesso !"));
                    die();
                } catch (Exception $e) {                
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $userID));
                    die();
                }    
                
                break;
    
            case 'bulkCreateProfiles':
                $profiles = $_FILES['profiles'];

                $keys = array(
                    "nome", "apelido", "title", "email", "linkedin",
                    "short_description_pt", "short_description_en", "descricao_pt", "descricao_en", "title_en"
                );
                
                $rowCounter = 0;
                $profileArray = array();
                
                if ( $xlsx = SimpleXLSX::parse($profiles['tmp_name']) ) {
                    foreach($xlsx->rows() as $i => $row){
                        if ($rowCounter <= 1) {
                            $rowCounter++;
                            continue;
                        }
                        
                        if ($row[0] === "" || !isset($row[0])) {
                            $_SESSION["actionError"] = "Perfil sem Nome Encontrado ! Todos os perfis devem ter um Nome !";
                            header('Location: /admin/profiles');
                        }

                        $profileArray[] = array_combine(
                            $keys, $row
                        );
                        
                        $rowCounter++;
                    };
                } else {
                    echo SimpleXLSX::parseError();
                }

                $bulkCreateResult = Member::bulkCreateMembers($profileArray);
            
                if ($bulkCreateResult["success"]) {
                    $_SESSION["actionSuccess"] = "Perfis Adicionados com Sucesso !";
                    header('Location: /admin/profiles');
                } else {
                    $errorMessage = "Erro ao adicionar perfil. ".count($bulkCreateResult["reprovedProfiles"])." Nomes repetidos !";
                    $_SESSION["actionError"] = $errorMessage;
                    header('Location: /admin/profiles');
                }

                break;

            case 'uploadImage': 

                if (!isset($_POST['profileID'])) {
                    $_SESSION["actionError"] = "Perfil Não Encontrado.";
                    header('Location: '.$_SERVER['HTTP_REFERER']);
                    die();
                }

                $profile = new Member($_POST['profileID']);
                $nickname = strtolower(remove_accents($profile->nickname_getter())); 

                $imageString = $_POST['image'];

                $returnPath = 'img_site/'.$nickname.'.png';
                $imageUploadPath = __DIR__.'/../img_site/'.$nickname.'.png';

                $imageMimetype = explode(";", $imageString); //image/png
                $imageEncodeType = explode(",", $imageMimetype[1]); //base64
                $imageData = base64_decode($imageEncodeType[1]);

            
                $uploadedFile = imagecreatefromstring($imageData);
                $mask = imagecreatefrompng(__DIR__."/../img_site/admin-profile-mask.png");
                $overlay = imagecreatefrompng(__DIR__."/../img_site/admin-profile-overlay.png");

                if ($uploadedFile === false || $mask === false || $overlay === false) {
                    http_response_code(401);

                    echo json_encode(array("success" => FALSE));
                    die();
                }

                applyImageAlphaMask($uploadedFile, $mask);
                overlayPNG($uploadedFile, $overlay);
                imagepng($uploadedFile, $imageUploadPath);

                Member::updateProfilePicture($profile->id_getter(), $returnPath);
                echo json_encode(array("uploadedImagePath" => $returnPath));

                die();
                break;
            }

        
    }

    
    die();
    
?>