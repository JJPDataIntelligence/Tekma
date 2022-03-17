<?php require_once(__DIR__."/../../model/User.php");?>
<?php require_once(__DIR__."/../../model/Member.php"); ?>
<?php if (!isset($_SESSION)) session_start();?>
<?php 
    if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
        if (!$user->isAdmin()) {
            header('Location: /admin');
            die();
        }
    }
?>

<?php 
    $profileID = array_pop(explode("/", $_SERVER["REQUEST_URI"]));
    $profile = new Member($profileID);
    $owner = $profile->getOwner();
?>


<?php function makeShownName($fullName, $nickname) {
    $pos = strpos($fullName, $nickname);
    if ($pos !== false) {
        return substr_replace($fullName, "<span class='orange'>".$nickname."</span>", $pos, strlen($nickname));
    } else {
        return $fullName." <span class='orange'>\"".$nickname."\"</span>";
    }
}?>

<?php function isTranslated(Member $profile) {
    return  $profile->englishShortDescription_getter() !== null &&
            $profile->englishShortDescription_getter() !== "" &&
            $profile->englishDescription_getter() !== null &&
            $profile->englishDescription_getter() !== "";
}?>
<div class="dashboard-container">
     
    <?php include_once(__DIR__.'/../navbar.php'); ?>
 
    <div class="container dashboard-body">
        <div class="container-fluid jumbotron">
            <div class='row'>
                <div class='col-sm-9' >
                    <div class='page-header'>
                        <h3 class='clearfix'>
                            <?php echo makeShownName($profile->name_getter(), $profile->nickname_getter());?>
                            <?php if($profile->isActive()){ ?>
                                <span class='badge pull-right progress-bar-success'>ATIVO</span>
                            <?php } else { ?>
                                <span class='badge pull-right progress-bar-danger'>INATIVO</span>
                            <?php } ?>
                        </h3>
                        <h2><small>[<span>🇧🇷<span>] <?php echo $profile->portugueseTitle_getter();?></small><br/>
                        <small>[<span>🇺🇸<span>] <?php echo $profile->englishTitle_getter();?></small></h2>
                    </div>
                    
                    <div class="profile-body">
                        <div class='row'>
                            <div class='col-xs-1'>
                                <img src="/img_site/ico_email.png" style="width: 20px;"/>
                            </div>
                            <div class='col-xs-10'>
                                <p><?php echo $profile->email_getter();?></p>
                            </div>
                        </div>

                        <div class='row'>
                            <div class='col-xs-1'>
                                <img src="/img_site/newLinkedinIcon.png" style="width: 20px;"/>
                            </div>
                            <div class='col-xs-10'>
                                <p><a href='<?php echo $profile->linkedin_getter();?>'><?php echo $profile->linkedin_getter();?></a></p>
                            </div>
                        </div>

                        <div class='row'>
                            <blockquote class='clearfix' id="portugueseShortDescription">
                                <footer><b>Descrição Curta</b><span class='pull-right'>🇧🇷<span></footer>
                                <?php echo $profile->portugueseShortDescription_getter();?>
                            </blockquote>
                        </div>
                    </div>
                </div>

                <div class='col-sm-3 thumbnail-container'>
                    <?php if ($profile->image_getter() !== null) {
                        echo "<img id='uploaded_image' src='/".(substr($profile->image_getter(), 0, 9) === "img_site/" ? $profile->image_getter() : "img_site/".$profile->image_getter())."' class='img-thumbnail default-thumbnail'/>";
                    } else {
                        echo "<img id='uploaded_image' src='/img_site/admin-navbar-logo.png' class='img-thumbnail default-thumbnail'/>";
                    }; ?>
                    <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                </div>
            </div>

            <div class='row'>
                <div class="profile-body">

                    <blockquote class='clearfix' id="portugueseLongDescription">
                        <footer><b>Descrição Longa</b><span class='pull-right'>🇧🇷<span></footer>
                        <?php echo $profile->portugueseDescription_getter(); ?>
                    </blockquote>

                    <blockquote class='clearfix' <?php echo isTranslated($profile) ? "" : "style='display:none'" ?> id="englishShortDescription">
                        <footer><b>Descrição Curta</b><span class='pull-right'>🇺🇸<span></footer>
                        <span><?php if(isTranslated($profile)) echo $profile->englishShortDescription_getter(); ?></span>
                    </blockquote>

                    <blockquote class='clearfix' <?php echo isTranslated($profile) ? "" : "style='display:none'" ?> id="englishLongDescription">
                        <footer><b>Descrição Longa</b><span class='pull-right'>🇺🇸<span></footer>
                        <span><?php if(isTranslated($profile)) echo $profile->englishDescription_getter(); ?></span>
                    </blockquote>

                </div>
            </div>

            <br/>
            <div class='row clearfix'>
                <div class='pull-left'>
                    <?php if ($profile->isActive()) { ?>
                    <a class='btn btn-danger' href='/admin/profileActions.php?action=deactivate&id=<?php echo $profile->id_getter(); ?>'>Desativar Perfil</a>
                    <?php } else { ?>
                    <a class='btn btn-success' href='/admin/profileActions.php?action=activate&id=<?php echo $profile->id_getter(); ?>'>Ativar Perfil</a>
                    <?php } ?>
                    
                    <?php if ($owner) { ?>
                    <a class='btn btn-warning' href="/admin/profileActions.php?action=release&user=<?php echo $owner->username; ?>">Desvincular Perfil</a>
                    <?php } else { ?>
                    <button class='btn btn-success' data-toggle="modal" data-target="#userClaimModal">Vincular Perfil</button>
                    <?php } ?>

                </div>
                <div class='pull-right'>
                    <label for='upload-image' class='btn btn-info'>Alterar Imagem</label>
                    <input type='file' name='image' id='upload-image' style='display: none'/>
                    <button class='btn btn-info' data-toggle="modal" data-target="#translationModal">Traduzir Perfil</button>
                    <a class='btn btn-info' href='/admin/edit/<?php echo $profile->id_getter(); ?>'>Editar Perfil</a>
                </div>
            </div>

        </div>

        <!-- Cropper Modal -->
        <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Recorte a sua Imagem</h4>
                    </div>
                    <div class="modal-body" style='padding-top: 0; padding-bottom: 0'>
                        <div class="img-container">
                            <div class="row">
                                <div class="col-md-8" style='width: 100%; padding: 0'>
                                    <img src="" id="sample_image" style='height: 500px;'/>
                                </div>
                                <div class="col-md-4">
                                    <div class="preview"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="crop" class="btn btn-primary">Recortar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Translation Confirmation Modal -->
        <div class="modal fade" id="translationModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">   
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Cuidado !</h4>
                    </div>         
                    <div class="modal-body">
                        <p>Ao utilizar a tradução automática no seu perfil,
                            qualquer tradução anterior será perdida.</p>    
                        <b>Tem certeza que deseja continuar a executar esta ação ? </b>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="translationButton" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- User Claim Modal -->
        <div class="modal fade" id="userClaimModal" tabindex="-1" role="dialog" aria-labelledby="userClaimModal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">   
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Vincular Usuário !</h4>
                    </div>         
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <p>Você está prestes a vincular um usuário ao perfil de <b><?php echo $profile->name_getter(); ?></b>.</p>    
                            </div>
                            <br/>
                            <?php include_once(__DIR__."/../userClaimForm.php") ?>
                            <br/>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <script>

            $(document).ready(function() {

                var $modal = $('#modal');
                var image = document.getElementById('sample_image');
                var cropper;

                var portugueseLongDescription = $('#portugueseLongDescription');
                var portugueseShortDescription = $('#portugueseShortDescription');
                var englishLongDescription = $('#englishLongDescription');
                var englishShortDescription = $('#englishShortDescription');

                $('#translationButton').on('click', () => {
                    let longDescriptionText = portugueseLongDescription.html().substr(portugueseLongDescription.html().indexOf('</footer>') + 9).trim()
                    let shortDescriptionText = portugueseShortDescription.html().substr(portugueseShortDescription.html().indexOf('</footer>') + 9).trim()
                    $.ajax({
                        url:'/admin/translate.php',
                        method:'POST',
                        data:{     
                            "profileID" : <?php echo $profileID; ?>,
                            text: {
                                "longDescriptionText": longDescriptionText,
                                "shortDescriptionText": shortDescriptionText
                            }
                        },
                        success: function(data) {
                            englishShortDescription.find('span').last().html(data.translatedText.shortDescriptionText);
                            englishLongDescription.find('span').last().html(data.translatedText.longDescriptionText);

                            englishShortDescription.slideDown();
                            englishLongDescription.slideDown();
                        },
                        error: function(err) {
                            console.error(err);
                        },
                        complete: function () {
                            $('#translationModal').modal('hide');
                        }
                    });
                });

                $('#upload-image').change(function(event) {
                    var files = event.target.files;

                    var done = function(url){
                        image.src = url;
                        $modal.modal('show');
                    };

                    if(files && files.length > 0) {
                        reader = new FileReader();
                        reader.onload = function(event) {
                            done(reader.result);
                        };
                        reader.readAsDataURL(files[0]);
                    }
                });

                $modal.on('shown.bs.modal', function() {
                    cropper = new Cropper(image, {
                        aspectRatio: 0.85,
                        viewMode: 2,
                        preview:'.preview'
                    });
                }).on('hidden.bs.modal', function() {
                    cropper.destroy();
                    cropper = null;
                });

                $('#crop').click(function(){
                    canvas = cropper.getCroppedCanvas({
                        width:400,
                        height:400
                    });

                    canvas.toBlob(function(blob) {
                        url = URL.createObjectURL(blob);
                        var reader = new FileReader();
                        reader.readAsDataURL(blob);
                        reader.onloadend = function(){
                            var base64data = reader.result;
                            $.ajax({
                                url:'/admin/profileActions.php',
                                method:'POST',
                                headers: {
                                    'Cache-Control': 'no-cache, must-revalidate' 
                                },
                                data:{
                                    profileID: <?php echo $profileID; ?>,
                                    action: 'uploadImage',
                                    image: base64data
                                },
                                success:function(data) {
                                    console.log(data);
                                    console.log(JSON.parse(data));
                                    let result = JSON.parse(data);
                                    $modal.modal('hide');
                                    // The next line includes a workaround to get the image to update bypassing cache.
                                    // Thats far from the "optimal" approach, I know. But it's 3am and thats the better one that comes to mind.
                                    // remember to revisit this if it ever bottlenecks the application. 'Till then, this will do.
                                    $('.img-thumbnail').attr('src', '/' + result.uploadedImagePath + '?' + new Date().getTime());
                                }
                            });
                        };
                    });
                });
            });
        </script>
    </div>

    <script>
        <?php
            echo    "jSuites.dropdown(document.getElementById('userSelector'), {
                        url: '/admin/fetch/users',
                        autocomplete: true,
                        lazyLoading: true,
                        onchange: function (element, index, oldValue, newValue, oldLabel, newLabel) {
                            document.getElementById('user').value = newValue;
                        }
                    });";
        ?>

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