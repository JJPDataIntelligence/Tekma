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
        $user->hydrateProfile();

        if (!$user->isAdmin()) {
            $_SESSION['actionError'] = "Você não tem permissão para acessar esta página !";
            header('Location: /admin');
        }
    }
?>

<?php if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_POST) || !isset($_POST["action"])) {
        $_SESSION['actionError'] = "Ação Inválida !";
        header('Location: /admin/users');
    }

    switch ($_POST["action"]) {
        case "createUser":
            $username = strtolower($_POST["username"]);
            $password = $_POST["password"];
            $active = isset($_POST["active"]) ? 1 : 0;
            $admin = isset($_POST["admin"]) ? 1 : 0;
            
            try {
                User::createUser($username, $password, $active, $admin);
                $_SESSION['actionSuccess'] = "Usuário Criado com Sucesso !";
                header('Location: /admin/users');
            } catch (Exception $e) {
                $_SESSION['actionError'] = $e->getMessage();
                header('Location: /admin/users');
            }

            break;
        
        case "bulkCreateUsers":
            $users = $_FILES['users'];

            $keys = array('username', 'password', 'active', 'admin');
            $rowCounter = 0;
            $userArray = array();
            if ( $xlsx = SimpleXLSX::parse($users['tmp_name']) ) {                
                foreach($xlsx->rows() as $i => $row){
                    if ($rowCounter <= 1) {
                        $rowCounter++;
                        continue;
                    }
                    
                    if ($row[0] === "" || !isset($row[0])) {
                        $_SESSION["actionError"] = "Usuário sem Nome de Usuário Encontrado ! Todos os usuários devem ter um Nome de Usuário !";
                        header('Location: /admin/users');
                    }

                    if ($row[1] === "" || !isset($row[1])) {
                        $_SESSION["actionError"] = "Usuário sem senha encontrado (".$row[0].") ! Todos os usuários devem ter uma senha cadastrada !";
                        header('Location: /admin/users');
                    }

                    $userArray[] = array_combine(
                        $keys, 
                        array($row[0], password_hash($row[1], PASSWORD_DEFAULT), $row[2] === "" ? 0 : $row[2], $row[3] === "" ? 0 : $row[3])
                    );
                    
                    $rowCounter++;
                };
            } else {
                echo SimpleXLSX::parseError();
            }
            
            $bulkCreateResult = User::bulkCreateUsers($userArray);
        
            if ($bulkCreateResult["success"]) {
                $_SESSION["actionSuccess"] = "Usuários Adicionados com Sucesso !";
                header('Location: /admin/users');
            } else {
                $errorMessage = "Erro ao adicionar usuário. ".count($bulkCreateResult["reprovedUsers"])." Nome de usuário repetido !";
                $_SESSION["actionError"] = $errorMessage;
                header('Location: /admin/users');
            }

            break;

        case "removeUser":
            $userID = $_POST["id"];
            
            try {
                User::removeUser($userID);
                echo json_encode(array("success" => true, "message" => "Usuário Removido com Sucesso !"));
                die();
            } catch (Exception $e) {                
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $userID));
                die();
            }

            break;
        case "deactivateUser":
            $userID = $_POST["id"];
            
            try {
                User::toggleUserState($userID, "deactivate");
                echo json_encode(array("success" => true, "message" => "Usuário Desativado com Sucesso !"));
                die();
            } catch (Exception $e) {                
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $userID));
                die();
            }
        
        case "activateUser":
            $userID = $_POST["id"];
            
            try {
                User::toggleUserState($userID, "activate");
                echo json_encode(array("success" => true, "message" => "Usuário Ativado com Sucesso !"));
                die();
            } catch (Exception $e) {                
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $userID));
                die();
            }
    
        case "deactivateUserAdmin":
            $userID = $_POST["id"];
            
            try {
                User::toggleUserAdminState($userID, "deactivate");
                echo json_encode(array("success" => true, "message" => "Nível Administrador de Usuário Desativado com Sucesso !"));
                die();
            } catch (Exception $e) {                
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $userID));
                die();
            }
        
        case "activateUserAdmin":
            $userID = $_POST["id"];
            
            try {
                User::toggleUserAdminState($userID, "activate");
                echo json_encode(array("success" => true, "message" => "Nível Administrador de Usuário Ativado com Sucesso !"));
                die();
            } catch (Exception $e) {                
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => $e->getMessage(), "userID" => $userID));
                die();
            }

        case "searchUsers":
            if (!isset($_POST["searchTerm"])) {
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => "Deve Conter um Termo de Busca"));
                die();
            }
            
            $searchTerm = $_POST["searchTerm"];
            
            try {
                $users = User::findUsers($searchTerm, $_POST["limit"] ? $_POST["limit"] : null, $_POST["offset"] ? $_POST["offset"] : null);
                echo json_encode(array("success" => true, "users" => $users));
                die();
            } catch (Exception $e) {                
                http_response_code(500);
                echo json_encode(array("success" => false, "message" => $e->getMessage()));
                die();
            }

    }

} else {
    header('Location: '.$_SERVER["HTTP_REFERER"]);
}?>