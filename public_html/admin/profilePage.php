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

<div class="container-fluid jumbotron">
    <div class='row'>
        <div class='col-sm-9' >
            <div class='page-header'>
                <h3 class='clearfix'>
                    <?php echo makeShownName($user->profile->name_getter(), $user->profile->nickname_getter());?>
                    <?php if($user->profile->isActive()){ ?>
                        <span class='badge pull-right progress-bar-success'>ATIVO</span>
                    <?php } else { ?>
                        <span class='badge pull-right progress-bar-danger'>INATIVO</span>
                    <?php } ?>
                    <?php if($user->isAdmin()){ ?>
                        <span class='badge pull-right progress-bar-warning' style='margin-right: 15px;'>ADMINISTRADOR</span>
                    <?php } ?>
                </h3>
                <h2><small><?php echo $user->profile->title_getter();?></small></h2>            
            </div>
            
            <div class="profile-body">
                <div class='row'>
                    <div class='col-xs-1'>
                        <img src="/img_site/ico_email.png" style="width: 20px;"/>
                    </div>
                    <div class='col-xs-10'>
                        <p><?php echo $user->profile->email_getter();?></p>
                    </div>
                </div>

                <div class='row'>
                    <div class='col-xs-1'>
                        <img src="/img_site/newLinkedinIcon.png" style="width: 20px;"/>
                    </div>
                    <div class='col-xs-10'>
                        <p><a href='<?php echo $user->profile->linkedin_getter();?>'><?php echo $user->profile->linkedin_getter();?></a></p>
                    </div>
                </div>

                <div class='row'>
                    <blockquote class='clearfix' id="portugueseShortDescription">
                        <footer><b>Descrição Curta</b><span class='pull-right'>🇧🇷<span></footer>
                        <?php echo $user->profile->portugueseShortDescription_getter();?>
                    </blockquote>
                </div>
            </div>
        </div>

        <div class='col-sm-3 thumbnail-container'>
            <?php if ($user->profile->image_getter() !== null) {
                echo "<img id='uploaded_image' src='/".(substr($user->profile->image_getter(), 0, 9) === "img_site/" ? $user->profile->image_getter() : "img_site/".$user->profile->image_getter())."' class='img-thumbnail default-thumbnail'/>";
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
                <?php echo $user->profile->portugueseDescription_getter(); ?>
            </blockquote>

            <blockquote class='clearfix' <?php echo isTranslated($user->profile) ? "" : "style='display:none'" ?> id="englishShortDescription">
                <footer><b>Descrição Curta</b><span class='pull-right'>🇺🇸<span></footer>
                <span><?php if(isTranslated($user->profile)) echo $user->profile->englishShortDescription_getter(); ?></span>
            </blockquote>

            <blockquote class='clearfix' <?php echo isTranslated($user->profile) ? "" : "style='display:none'" ?> id="englishLongDescription">
                <footer><b>Descrição Longa</b><span class='pull-right'>🇺🇸<span></footer>
                <span><?php if(isTranslated($user->profile)) echo $user->profile->englishDescription_getter(); ?></span>
            </blockquote>

        </div>
    </div>

    <br/>
    <div class='row clearfix'>
        <div class='pull-left'>
            <?php if($user->profile->isActive()) { ?>
            <a class='btn btn-danger' href="/admin/profileActions.php?action=deactivate">Desativar Perfil</a>
            <?php } else { ?>
            <a class='btn btn-success' href="/admin/profileActions.php?action=activate">Ativar Perfil</a>
            <?php } ?>
            <a class='btn btn-warning' href="/admin/profileActions.php?action=release">Desvincular Perfil</a>
        </div>
        <div class='pull-right'>
            <label for='upload-image' class='btn btn-info'>Alterar Imagem</label>
            <input type='file' name='image' id='upload-image' style='display: none'/>
            <button class='btn btn-info' data-toggle="modal" data-target="#translationModal">Traduzir Perfil</button>
            <a class='btn btn-info' href='/admin/edit'>Editar Perfil</a>
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
                        url:'/admin/updateProfile.php',
                        method:'POST',
                        headers: {
                            'Cache-Control': 'no-cache, must-revalidate' 
                        },
                        data:{
                            action: 'uploadImage',
                            image: base64data
                        },
                        success:function(data) {
                            let result = JSON.parse(data);
                            $modal.modal('hide');
                            // The next line includes a workaround to get the image to update bypassing cache.
                            // Thats far from the "optimal" approach, I know. But it's 3am and thats the better one that comes to mind.
                            // remember to revisit this if it ever bottlenecks the application. 'Till then, this will do.
                            $('.img-thumbnail').attr('src', `${
                                result.uploadedImagePath.startsWith("img_site") ?
                                `/${result.uploadedImagePath}` :
                                `/img_site/${result.uploadedImagePath}`
                            }?${new Date().getTime()}`);
                        }
                    });
                };
            });
        });
    });
</script>