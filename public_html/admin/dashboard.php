<?php require_once(__DIR__."/../model/User.php");?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php if (!isset($_SESSION)) session_start();?>
<?php 
    if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
        $user->hydrateProfile();
    }
?>

<?php function welcomeMessage($to) {
    return "<h3>Olá, @".$to." !</h3>";
}?>

<div class="dashboard-container">
     
    <?php include_once(__DIR__.'/navbar.php'); ?>

    <div class="container dashboard-body">

        <?php if ($user->profileID_getter() === null) {
                echo    welcomeMessage($user->username_getter());
                echo    "<div class='well'>
                            Não encontramos nenhum perfil de exibição associado a este usuário.<br/>
                            Caso você já possua um perfil de exibição, selecione-o.
                        </div><br/>";
                include_once(__DIR__.'/profileClaimForm.php');
                echo    "<br/><div class='well well-sm'>
                            Caso contrário, prossiga com a criação do seu perfil.
                        </div>";
                include_once(__DIR__.'/profileCreateForm.php');
                echo    "<br/><br/>";
            } else {
                include_once(__DIR__.'/profilePage.php');   
        }?>

    </div>

    <script>
        <?php if ($user->profileID_getter() === null) {
            echo    "jSuites.dropdown(document.getElementById('profileSelector'), {
                        url: '/admin/fetch/cooperative-partners',
                        autocomplete: true,
                        lazyLoading: true,
                        onchange: function (element, index, oldValue, newValue, oldLabel, newLabel) {
                            document.getElementById('profile').value = newValue;
                        }
                    });";
        }?>

        /* Claim */
        <?php if (isset($_SESSION['claimError'])) {
            echo    "jSuites.notification({
                error: 1,
                name: 'Erro de Reivindicação de Perfil',
                message: '".$_SESSION['claimError']."',
            })"; 
            unset($_SESSION['claimError']);
        }?>

        <?php if (isset($_SESSION['claimSuccess'])) {
            echo    "jSuites.notification({
                name: '".$user->profile."',
                message: '".$_SESSION['claimSuccess']."'
            })"; 
            unset($_SESSION['claimSucess']);
        }?>

        /* Create */
        <?php if (isset($_SESSION['createError'])) {
            echo    "jSuites.notification({
                error: 1,
                name: 'Erro de Criação de Perfil',
                message: '".$_SESSION['createError']."',
            })"; 
            unset($_SESSION['createError']);
        }?>

        /* Activation */
        <?php if (isset($_SESSION['actionError'])) {
            echo    "jSuites.notification({
                name: 'Erro ao Executar a Ação Desejada',
                message: '".$_SESSION['actionError']."'
            })"; 
            unset($_SESSION['actionError']);
        }?>
        <?php if (isset($_SESSION['actionSuccess'])) {
            echo    "jSuites.notification({
                name: '".$user->profile."',
                message: '".$_SESSION['actionSuccess']."'
            })"; 
            unset($_SESSION['actionSuccess']);
        }?>
    </script>

    
</div>