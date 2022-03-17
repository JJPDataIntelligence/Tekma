<?php 

    function makeConnection() {
        unset($conn);

        $connSettings = [
            "dbHost" => 'db_tekma.mysql.dbaas.com.br',
	        "dbName" => 'db_tekma',
	        "dbUser" => 'db_tekma',
	        "dbPwd" => 'Tekma#2020'
        ];

        try {
            $conn = new PDO(
                "mysql:host=".$connSettings["dbHost"].";dbname=".$connSettings["dbName"],
                $connSettings["dbUser"],
                $connSettings["dbPwd"],
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
            );
        } catch (PDOException $e) {
            echo "<script>console.error(`Connection Error: ".$e."`);</script>";
            die();
        }

        return $conn;

    }

    function terminateConnection() {
        if (isset($conn)) unset($conn);
    }
?>