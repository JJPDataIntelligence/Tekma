<?php session_start(); ?>

<?php $path = explode("/", rtrim($_SERVER["REQUEST_URI"], "/"));?>
<?php 
    function isSessionOpen() {
        return isset($_SESSION) && isset($_SESSION["user"]);
    }
?>

<?php 
    /*
     * This must be here due to the fact that it returns a JSON Response. So if it got placed in the router,
     * e.g. inside the main HTML, it would conflict. So, think about this portion as 
     * a sort of "middleware router for non HTML Responses".
     */ 
    if (isSessionOpen()) {
        if (isset($path[2]) && substr($path[2], 0, 5) == "fetch") {
            include_once(__DIR__."/fetch.php");
            die();
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="/img_site/admin-navbar-logo.png">
        <title>Painel Administrativo - Tekma</title>
        <link href="/css/bootstrap.css" rel="stylesheet">
        <link href="/css/font-awesome.css" rel="stylesheet">
        <link href="/css/admin.css" rel="stylesheet">
        <script src="/js/jquery.js"></script>
        <script src="/js/bootstrap.js"></script>

        <link  href="/css/cropper.css" rel="stylesheet">
        <link rel="stylesheet" href="https://jsuites.net/v4/jsuites.css" type="text/css"/>
        <script src="https://jsuites.net/v4/jsuites.js"></script>
        <script src="/js/cropper.js"></script><!-- Cropper.js is required -->
        <script src="/js/jquery-cropper.js"></script>
    </head>
    <body>
        <?php if (isSessionOpen()) { echo "<div class='main'>"; } ?>
            <div class="loading-modal"></div>
            <?php
                if (isSessionOpen()) {
                
                    if (!isset($path[2])) {
                        include_once(__DIR__."/dashboard.php");
                    } else if (substr($path[2], 0, 7) == "edit-bu") {
                        include_once(__DIR__."/editBU.php");
                    } else if (substr($path[2], 0, 4) == "edit") {
                        include_once(__DIR__."/editProfile.php");
                    } else if (substr($path[2], 0, 5) == "users") {
                        include_once(__DIR__."/adminRoutes/users.php");
                    } else if (substr($path[2], 0, 8) == "profiles") {
                        include_once(__DIR__."/adminRoutes/profiles.php");
                    } else if (substr($path[2], 0, 7) == "profile") {
                        include_once(__DIR__."/adminRoutes/profile.php");
                    } else if (substr($path[2], 0, 14) == "business-units") {
                        include_once(__DIR__."/adminRoutes/business-units.php");
                    } else if (substr($path[2], 0, 13) == "business-unit") {
                        include_once(__DIR__."/adminRoutes/business-unit.php");
                    } 
                } else {
                    if (!isset($path[2])) {
                        header("Location: /admin/login");
                    } else if (substr($path[2], 0, 8) == "new-user") {
                        include_once(__DIR__."/register.php");
                    } else if (substr($path[2], 0, 5) == "login") {
                        include_once(__DIR__."/login.php");
                    } else {
                        header("Location: /admin/login");
                    }
                }
            ?>
        <?php if (isSessionOpen()) echo "</div>"; ?>
        
        <div class="<?php if(!isSessionOpen()) echo "footer"; else echo "custom-footer"; ?>">
            <span class="text-center">por JJP Data Intelligence ©</span>
        </div>
        
        
        
        <script>
            $(document).ready(function(){

                $('[data-toggle="tooltip"]').tooltip({
                    trigger : 'hover'
                })  

                $body = $("body");
                $(document).on({
                    ajaxStart: function() { $body.addClass("loading");    },
                    ajaxStop: function() { $body.removeClass("loading"); }    
                });

            });
        </script>
    </body>
</html>