<?php require_once(__DIR__."/../model/User.php");?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php require_once(__DIR__."/../model/BusinessUnit.php"); ?>
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

<?php 

    if ($_SERVER["REQUEST_METHOD"] === "GET") {
        switch ($_GET["action"]) {
            default:
                die();    
                break;

        }
        
    } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
        switch ($_POST["action"]) {
            
            case 'createBU': 
                if (!isset($_POST["name"])) {
                    $_SESSION["actionError"] = "Informações Obrigatórias Não Foram Encontradas";
                    header('Location: /admin/business-units');
                    die();
                }
                
                $name = $_POST["name"];

                // Create BU & DB Record
                try {
                    $businessUnitID = BusinessUnit::createBU($name);
                    $_SESSION["actionSuccess"] = "Unidade de Negócio Adicionada com Sucesso !";
                } catch (Exception $e) {
                    $_SESSION["actionError"] = $e->getMessage();
                    header('Location: /admin/business-units');
                    die();
                }              

                header('Location: /admin/business-units');
                break;

    
            case 'removeBU':
                $businessUnitID = $_POST["id"];
            
                try {
                    BusinessUnit::removeBU($businessUnitID);
                    echo json_encode(array("success" => true, "message" => "Unidade de Negócio Removida com Sucesso !"));
                    die();
                } catch (Exception $e) {                
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $businessUnitID));
                    die();
                }    
                
                break;
            case "searchBUS":
                if (!isset($_POST["searchTerm"])) {
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => "Deve Conter um Termo de Busca"));
                    die();
                }
                
                $searchTerm = $_POST["searchTerm"];
                
                try {
                    $businessUnits = BusinessUnit::findBUS($searchTerm, $_POST["limit"] ? $_POST["limit"] : null, $_POST["offset"] ? $_POST["offset"] : null);
                    echo json_encode(array("success" => true, "businessUnits" => $businessUnits));
                    die();
                } catch (Exception $e) {                
                    http_response_code(500);
                    echo json_encode(array("success" => false, "message" => $e->getMessage()));
                    die();
                }

            case 'bulkCreateBUS':
                $profiles = $_FILES['bus'];

                $keys = array(
                    "name", "description_pt", "description_en"
                );
                
                $rowCounter = 0;
                $busArray = array();
                
                if ( $xlsx = SimpleXLSX::parse($profiles['tmp_name']) ) {
                    foreach($xlsx->rows() as $i => $row){
                        if ($rowCounter <= 1) {
                            $rowCounter++;
                            continue;
                        }
                        
                        if ($row[0] === "" || !isset($row[0])) {
                            $_SESSION["actionError"] = "Unidade de Negócio sem Nome Encontrada ! Todas as Unidades de Negócio devem ter um Nome !";
                            header('Location: /admin/business-units');
                        }

                        $busArray[] = array_combine(
                            $keys, $row
                        );
                        
                        $rowCounter++;
                    };
                } else {
                    echo SimpleXLSX::parseError();
                }

                $bulkCreateResult = BusinessUnit::bulkCreateBUS($busArray);
            
                if ($bulkCreateResult["success"]) {
                    $_SESSION["actionSuccess"] = "Unidades de Negócio Adicionados com Sucesso !";
                    header('Location: /admin/business-units');
                } else {
                    $errorMessage = "Erro ao adicionar Unidades de Negócio. ".count($bulkCreateResult["reprovedBUS"])." Nomes repetidos !";
                    $_SESSION["actionError"] = $errorMessage;
                    header('Location: /admin/business-units');
                }

                break;

            case 'associateBUS':
                if (!isset($_POST["parent_id"])) {
                    $_SESSION["actionError"] = "Informações Obrigatórias Não Foram Encontradas";
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }
                
                $parent_id = $_POST["parent_id"];
                $children = array_unique($_POST["bus"] != null ? $_POST["bus"] : []);

                // Create BU & DB Record
                try {
                    $businessUnitID = BusinessUnit::associateBUS($parent_id, $children);
                    $_SESSION["actionSuccess"] = "Unidades de Negócio Associadas com Sucesso !";
                } catch (Exception $e) {
                    $_SESSION["actionError"] = $e->getMessage();
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }              

                header('Location: '.$_SERVER["HTTP_REFERER"]);
                break;

            case 'associateMembers':
                if (!isset($_POST["bu_id"])) {
                    $_SESSION["actionError"] = "Informações Obrigatórias Não Foram Encontradas";
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }
                
                $bu_id = $_POST["bu_id"];
                $members = array_unique(isset($_POST["members"]) && $_POST["members"] != null ? $_POST["members"] : []);

                // Create BU & DB Record
                try {
                    BusinessUnit::associateMembers($bu_id, $members);
                    $_SESSION["actionSuccess"] = "Membros Associados com Sucesso !";
                } catch (Exception $e) {
                    $_SESSION["actionError"] = $e->getMessage();
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }              

                header('Location: '.$_SERVER["HTTP_REFERER"]);
                break;

            case 'associateHead':
                if (!isset($_POST["bu_id"])) {
                    $_SESSION["actionError"] = "Informações Obrigatórias Não Foram Encontradas";
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }
                
                $bu_id = $_POST["bu_id"];
                $profile = $_POST["profile"];

                // Create BU & DB Record
                try {
                    BusinessUnit::associateHead($bu_id, $profile);
                    $_SESSION["actionSuccess"] = "Responsável Associado com Sucesso !";
                } catch (Exception $e) {
                    $_SESSION["actionError"] = $e->getMessage();
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }              

                header('Location: '.$_SERVER["HTTP_REFERER"]);
                break;

            case 'editBU':
                if (!isset($_POST["bu_id"])) {
                    $_SESSION["actionError"] = "Informações Obrigatórias Não Foram Encontradas";
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }
                
                try {
                    $bu = new BusinessUnit($_POST["bu_id"]);
                    
                    if (isset($_POST["name"])) {
                        $bu->updateName($_POST["name"]);
                    }
                    if (isset($_POST["description"])) {
                        $bu->updatePortugueseDescription($_POST["description"]);
                    }
                    if (isset($_POST["shortDescription"])) {
                        $bu->updatePortugueseShortDescription($_POST["shortDescription"]);
                    }
                    if (isset($_POST["englishDescription"])) {
                        $bu->updateEnglishDescription($_POST["englishDescription"]);
                    }
                    if (isset($_POST["englishShortDescription"])) {
                        $bu->updateEnglishShortDescription($_POST["englishShortDescription"]);
                    }

                    $_SESSION["actionSuccess"] = "Unidade de Negócio Alterada com Sucesso !";

                } catch (Exception $e) {
                    $_SESSION["actionError"] = $e->getMessage();
                    header('Location: '.$_SERVER["HTTP_REFERER"]);
                    die();
                }              

                header('Location: /admin/business-unit/'.$_POST["bu_id"]);
                break;


        }
    }

    
    die();
    
?>