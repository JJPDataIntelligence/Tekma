<?php session_start(); ?>

<div class="bg-image"></div>
<form name="registerForm" id="registerForm" enctype="multipart/form-data"
    method="POST" action="/admin/authenticate.php" >	
    <input type="hidden" name="action" value="register">
    <div class="login-container"> 
        <div style='padding:5px;' class='center'><img src="../img_site/logo_adm.png" border="0" alt="" class='img-responsive'></div>

        <div class='center'>
            <br><br>
    
            <?php 
                if (isset($_SESSION['authError'])){
                    echo "<div class='bs-example'>";
                    echo "<div class='alert alert-danger'>";
                    echo "<a href='#' class='close' data-dismiss='alert'>&times;</a>";
                    echo "<strong>".$_SESSION['authError']."</strong>";
                    echo "</div>";
                    echo "</div>";
                    unset ($_SESSION['authError']);
                }
            ?>
    
            <div style='padding:15px;'>	
            
                <div class="form-group first">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon" style='width:40px'><i class="fa fa-user"></i></span>
                    <input type="text" class="form-control" name="username" id="username"required placeholder="Nome de Usuário"/>	
                </div>
            </div>
        
            <div class="form-group last">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon" style='width:40px'><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control" name="password" id="password" required placeholder="Senha"/>
                </div>
            </div>

            <div class="form-group last">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon" style='width:40px'><i class="fa fa-key"></i></span>
                    <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" required placeholder="Confirmação de Senha"/>
                </div>
            </div>
        
            <button type="button" onClick="window.location.replace('/admin/login')" class="btn btn-default col-xs-5">Fazer Login</button>
            <div class="col-xs-1"></div>
            <button type="submit" class="btn btn-default col-xs-5">Registrar</button>
            <br/>
            <div class='clear'></div>
        
        </div>
    <div>
</form>