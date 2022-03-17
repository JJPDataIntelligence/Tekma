<?php 
    $path = explode("/", $_SERVER["REQUEST_URI"]);
    $currentRoute = array_pop($path);
    if (is_numeric($currentRoute)) {
        $currentRoute = array_pop($path);
    }
?>

<nav class="navbar navbar-default navbar-fixed-top">
    <div class="container-fluid">

        <div class="navbar-header">
            <a class="navbar-brand" href="/admin">
                <img alt="Tekma" src="/img_site/admin-navbar-logo.png" class="navbar-logo">
            </a>
        </div>

        <div class="collapse navbar-collapse navbar-right">
            
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-user"></i><span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu">
                        <?php 
                            if ($user->isAdmin()) {
                                echo "<li ".($currentRoute === "admin" ? "class='active'" : "")."><a href='/admin'>Meu Perfil</a></li>";
                                echo "<li role='separator' class='divider'></li>";
                                echo "<li class='dropdown-header'>Administrar</li>";
                                echo "<li ".($currentRoute === "users" ? "class='active'" : "")."><a href='/admin/users'>Usuários</a></li>";
                                echo "<li ".(in_array($currentRoute, array("profiles", "profile"), TRUE) ? "class='active'" : "")."><a href='/admin/profiles'>Cooperados</a></li>";
                                echo "<li ".($currentRoute === "business-units" ? "class='active'" : "")."><a href='/admin/business-units'>Unidades de Negócio</a></li>";
                                echo "<li role='separator' class='divider'></li>";
                            }
                        ?>
                        <li><a href="/admin/logout.php">Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>