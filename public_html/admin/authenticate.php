<?php require_once(__DIR__."/../model/User.php");?>
<?php require_once(__DIR__."/../connection.php");?>
<?php if (!isset($_SESSION)) session_start();?>

<?php 

    function invalidAuthentication(string $location = "/admin/login", string $error = "Usuário e/ou Senha Inválidos") {
        $_SESSION["authError"] = $error;
        header("Location: ".$location);
        die();
    }

    function getUser (string $username) {
        try {
            $user = new User($username);
            return $user;
        } catch (Exception $e) {            
            invalidAuthentication();
        }
    }

    function validateLogin (string $password, User $user) {
        if (password_verify($password, $user->passwordHash_getter())) {
            
            if ($user->isActive()) {
                $_SESSION["user"] = serialize($user);
                header("Location: /admin");
            } else {
                invalidAuthentication("/admin/login", "Usuário Inativo !");    
            }         
            
        } else {
            invalidAuthentication();
        }
    }


    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        
        switch ($_POST["action"]) {
            case 'authenticate':
                session_unset();
                
                if (isset($_POST["username"]) && isset($_POST["password"])) {
                    $user = getUser($_POST["username"]);
                    validateLogin($_POST["password"], $user);

                } else {
                    invalidAuthentication();
                }            

                break;
            
            case 'register': 

                if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["confirmPassword"])) {
                    
                    if ($_POST["password"] !== $_POST["confirmPassword"]) {
                        invalidAuthentication("/admin/new-user", "Senhas Diferentes");
                    }

                    $conn = makeConnection();

                    // This is not the better way, but the proper way would require PDOExceptions
                    // which I don't want to turn on for compatibility with existing code;

                    // Checking if username exists;
                    $QUERY = "SELECT COUNT(*) FROM users WHERE username = ?";
                    $SQL = $conn->prepare($QUERY);
                    $SQL->execute(array($_POST["username"]));
                    
                    if ($SQL->fetchColumn() == 1) {
                        invalidAuthentication("/admin/new-user", "Usuário Já Cadastrado");
                    } else {
                        // Create user if none exists;
                        $QUERY = "INSERT INTO users (username, password) VALUES (?, ?)";
                        $conn->prepare($QUERY)->execute([$_POST["username"], password_hash($_POST["password"], PASSWORD_DEFAULT)]);

                        // Check if user was created;
                        $QUERY = "SELECT COUNT(*) FROM users WHERE username = ?";
                        $SQL = $conn->prepare($QUERY);
                        $SQL->execute(array($_POST["username"]));

                        if ($SQL->fetchColumn() != 1) {
                            invalidAuthentication("/admin/new-user", "Erro ao Cadastrar Usuário.");
                        } else {
                            $_SESSION["registerSuccess"] = "Usuário Registrado Com Sucesso";
                            header("Location: /admin");
                        }
                    }

                } else {
                    invalidAuthentication("/admin/new-user");
                }

                break;

            default:
                invalidAuthentication();
                break;
        }
        

    }

?>