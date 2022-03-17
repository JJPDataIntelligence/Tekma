<?php 
    session_start();
    if (isset($_SESSION) && isset($_SESSION["user"])) {
        session_destroy();
        header("Location: /admin/login");
    }

?>