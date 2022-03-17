<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php require_once(__DIR__."/../model/User.php"); ?>
<?php if (!isset($_SESSION)) session_start();?>
<?php 
    if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
    }
?>

<?php

    $query = array_pop(explode("/", $_SERVER["REQUEST_URI"]));
    header("Content-Type: application/json");
    if ($query == "cooperative-partners") {
        $response = array();
        $members = Member::fetchUnclaimed();
        foreach ($members as $key => $member) {
            array_push($response, array("text" => $member["name"], "id" => $member["id"]));
        }

        echo json_encode($response);
    } else if ($query == "users") {
        $response = array();
        $users = User::fetchUnclaimed();
        foreach ($users as $key => $user) {
            array_push($response, array("text" => $user["username"], "id" => $user["username"]));
        }

        echo json_encode($response);
    } else {
        echo json_encode(array("error" => "INVALID QUERY STRING"));
    }

  
?>