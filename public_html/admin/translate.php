<?php require '../vendor/autoload.php'; use Google\Cloud\Translate\V2\TranslateClient; ?>
<?php require_once(__DIR__."/../model/User.php");?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php if (!isset($_SESSION)) session_start();?>
<?php if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
    }
?>

<?php function translateText(string $toBeTranslated, string $targetLanguage = 'en'): string {
    if (!isset($toBeTranslated) || $toBeTranslated === "") throw new Exception("Translation Error. Input string must not be empty.");

    $translatorClient = new TranslateClient([
        'key' => 'AIzaSyD4EtS0aCgzGxXQYhF4xXwnho9k8hEJ8xs'
    ]);

    $translation = $translatorClient->translate($toBeTranslated, [
        'target' => $targetLanguage
    ]);

    if (!isset($translation["text"])) throw new Exception("Translation Error");

    return $translation["text"];
}?>

<?php

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        header("Content-Type: application/json");
        if (!isset($_POST["text"]) || $_POST["text"] === "" ) {
            http_response_code(400);
            echo json_encode(array("status" => "error", "errorMessage" => "Incomplete Request"));
            die();
        } else {
            $responseArray = array();
            
            foreach ($_POST["text"] as $key => $value) {
                $responseArray[$key] = translateText($value);
            }

            if (isset($_POST["profileID"]) && $_POST["profileID"] !== "") {
                Member::updateMember($_POST["profileID"], [
                    "englishDescription" => $responseArray["longDescriptionText"],
                    "englishShortDescription" => $responseArray["shortDescriptionText"]
                ]);
            } else if ($user->profileID_getter() !== null && $user->profileID_getter() !== "") {
                Member::updateMember($user->profileID_getter(), [
                    "englishDescription" => $responseArray["longDescriptionText"],
                    "englishShortDescription" => $responseArray["shortDescriptionText"]
                ]);
            } else {
                http_response_code(400);
                echo json_encode(array("status" => "error", "errorMessage" => "Profile Not Found"));
                die();
            }

            echo json_encode(array("status" => "success", "translatedText" => $responseArray));
            die();
        }
        
    } else {
        header('Location: '.$_SERVER["HTTP_REFERER"]);
    }
?>