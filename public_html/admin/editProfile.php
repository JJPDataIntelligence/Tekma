<?php require_once(__DIR__."/../model/User.php");?>
<?php require_once(__DIR__."/../model/Member.php"); ?>
<?php if (!isset($_SESSION)) session_start();?>
<?php 
    if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
        
        $url = $_SERVER["REQUEST_URI"];
        $fullPath = explode("/", rtrim($url, "/"));
        $lastPath = array_pop($fullPath);
        
        if (count($fullPath) > 2) {
            $id = intval($lastPath);
            if (!isset($id)) {
                $_SESSION["actionError"] = "perfil Não Encontrado !";
                header('Location: /admin');
                die();
            }
         
            try {
                $profile = new Member($id);
            } catch (Exception $e) {
                $_SESSION["actionError"] = "Perfil Não Encontrado !";
                header('Location: /admin');
                die();
            }
        } else {
            $user->hydrateProfile();
            $profile = $user->profile;
        }
    }
?>

<div class="dashboard-container">
     
    <?php include_once(__DIR__.'/navbar.php'); ?>

    <div class="container dashboard-body">

        <form name='editProfileForm' id='editProfileForm' enctype='multipart/form-data' method='POST' action='/admin/updateProfile.php'>
            <input hidden id='editProfileForm-action' name='action' value='editProfile'/>
            <input hidden id='editProfileForm-profileID' name='profileID' value='<?php echo $profile->id_getter(); ?>'/>
            <div class="container-fluid jumbotron" style='padding: 15px;'>
                
                <div class='row'>
                    <div class='col-sm-6'>
                        <div class="input-group" style='width: 100%'>
                            <label for='editProfileForm-fullName'>Nome Completo</label>    
                            <input type="text" name="fullName" id="editProfileForm-fullName" class="form-control"
                                value='<?php echo $profile->name_getter();?>'/>
                        </div>
                    </div>

                    <div class='col-sm-6'>
                        <div class="input-group" style='width: 100%'>
                            <label for='editProfileForm-nickname'>Apelido</label>    
                            <input type="text" name="nickname" id="editProfileForm-nickname" class="form-control"
                                value='<?php echo $profile->nickname_getter();?>'/>
                        </div>
                    </div>
                </div>
                
                <br/>

                <div class='row'>
                    <div class='col-sm-6'>
                        <div class="input-group" style='width: 100%'>
                            <label for='editProfileForm-email'>Email</label>    
                            <input type="text" name="email" id="editProfileForm-email"  class="form-control"
                                value='<?php echo $profile->email_getter();?>'/>
                        </div>
                    </div>

                    <div class='col-sm-6'>
                        <div class="input-group" style='width: 100%'>
                            <label for='editProfileForm-linkedin'>LinkedIn</label>    
                            <input type="text" name="linkedin" id="editProfileForm-linkedin" class="form-control"
                                value='<?php echo $profile->linkedin_getter();?>'/>
                        </div>
                    </div>
                </div>

                <br/>

                <div class='row'>

                    <div class='col-sm-6'>
                        <div class="input-group" style='width: 100%'>
                            <label for='editProfileForm-title'>Título [<span>🇧🇷<span>] <i class="fa fa-info-circle" data-toggle="tooltip" title="Uma função ou título de destaque" aria-hidden="true"></i></label>
                            <input type="text" name="title" id="editProfileForm-title" class="form-control"
                                value='<?php echo $profile->portugueseTitle_getter();?>'/>
                        </div>
                    </div>

                    <div class='col-sm-6'>
                        <div class="input-group" style='width: 100%'>
                            <label for='editProfileForm-englishTitle'>Título [<span>🇺🇸<span>] <i class="fa fa-info-circle" data-toggle="tooltip" title="Uma função ou título de destaque" aria-hidden="true"></i></label>
                            <input type="text" name="englishTitle" id="editProfileForm-englishTitle" class="form-control"
                                value='<?php echo $profile->englishTitle_getter();?>'/>
                        </div>
                    </div>

                    

                </div>

                <div class='row'>
                    <div class='col-sm-12'>
                        <div class="input-group editor-container" style='width: 100%'>
                            <label for='editProfileForm-shortDescription' style='width: 100%;'>Descrição Curta<span class='pull-right'>🇧🇷<span></label>
                            <div class="demo-update__controls">
                                <textarea name="shortDescription" id="editProfileForm-shortDescription"
                                    class="form-control editor"><?php echo $profile->portugueseShortDescription_getter();?></textarea>
                                <svg class="demo-update__chart" viewbox="0 0 40 40" width="100" height="100" style='margin-left: 15px' xmlns="http://www.w3.org/2000/svg">
                                    <circle stroke="hsl(0, 0%, 93%)" stroke-width="3" fill="none" cx="20" cy="20" r="17" />
                                    <circle class="demo-update__chart__circle" stroke="hsl(202, 92%, 59%)" stroke-width="3" stroke-dasharray="134,534" stroke-linecap="round" fill="none" cx="20" cy="20" r="17" />
                                    <text class="demo-update__chart__characters" x="50%" y="50%" dominant-baseline="central" text-anchor="middle"></text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <br/>

                <div class='row'>
                    <div class='col-sm-12'>
                        <div class="input-group editor-container" style='width: 100%'>
                            <label for='editProfileForm-description' style='width: 100%;'>Descrição Completa<span class='pull-right'>🇧🇷<span></label>
                            <textarea name="description" id="editProfileForm-description" style='max-height: 300px' 
                                class="form-control editor"><?php echo $profile->portugueseDescription_getter();?></textarea>
                        </div>
                    </div>
                </div>
                
                <br/>

                <div class='row'>
                    <div class='col-sm-12'>
                        <div class="input-group english-editor-container" style='width: 100%'>
                            <label for='editProfileForm-englishShortDescription' style='width: 100%;'>Descrição Curta<span class='pull-right'>🇺🇸<span></label>
                            <div class="english-demo-update__controls">
                                <textarea name="englishShortDescription" id="editProfileForm-englishShortDescription"
                                    class="form-control editor"><?php echo $profile->englishShortDescription_getter();?></textarea>
                                <svg class="english-demo-update__chart" viewbox="0 0 40 40" width="100" height="100" style='margin-left: 15px' xmlns="http://www.w3.org/2000/svg">
                                    <circle stroke="hsl(0, 0%, 93%)" stroke-width="3" fill="none" cx="20" cy="20" r="17" />
                                    <circle class="english-demo-update__chart__circle" stroke="hsl(202, 92%, 59%)" stroke-width="3" stroke-dasharray="134,534" stroke-linecap="round" fill="none" cx="20" cy="20" r="17" />
                                    <text class="english-demo-update__chart__characters" x="50%" y="50%" dominant-baseline="central" text-anchor="middle"></text>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                
                <br/>

                <div class='row'>
                    <div class='col-sm-12'>
                        <div class="input-group editor-container" style='width: 100%'>
                            <label for='editProfileForm-englishDescription' style='width: 100%;'>Descrição Completa<span class='pull-right'>🇺🇸<span></label>
                            <textarea name="englishDescription" id="editProfileForm-englishDescription" style='max-height: 300px' 
                                class="form-control editor"><?php echo $profile->englishDescription_getter();?></textarea>
                        </div>
                    </div>
                </div>
                
                <br/>

                <div class="row">
                    <div class='col-sm-12 text-right'>
                        <a href='/admin' class='btn btn-default'>Cancelar</a>    
                        <button type='submit' class='btn btn-info'>Salvar</button>
                    </div>
                </div>

            </div>
        </form>

    </div>
    
</div>

<script src="/js/ckeditor.js"></script>
<script>
    $(window).bind('keydown', function(event) {
        if (event.ctrlKey || event.metaKey) {
            switch (String.fromCharCode(event.which).toLowerCase()) {
                case 's':
                    event.preventDefault();
                    $('button[type="submit"]').click();
                    break;
            }
        }
    });

    const sendButton = document.querySelector('button[type="submit"]');

    // PORTUGUESE
    const maxCharacters = 256;
    const container = document.querySelector('.editor-container');
    const progressCircle = document.querySelector('.demo-update__chart__circle');
    const charactersBox = document.querySelector( '.demo-update__chart__characters' );
    const circleCircumference = Math.floor( 2 * Math.PI * progressCircle.getAttribute( 'r' ) );

    // ENGLISH
    const englishMaxCharacters = 256;
    const englishContainer = document.querySelector('.english-editor-container');
    const englishProgressCircle = document.querySelector('.english-demo-update__chart__circle');
    const englishCharactersBox = document.querySelector( '.english-demo-update__chart__characters' );
    const englishCircleCircumference = Math.floor( 2 * Math.PI * englishProgressCircle.getAttribute( 'r' ) );


    Promise.all([
        ClassicEditor.create(document.querySelector('#editProfileForm-shortDescription'), {
            toolbar: {
                items: [
                    '|',
                    'bold',
                    'italic',
                    'strikethrough',
                    'underline',
                    'subscript',
                    'superscript',
                    'link',
                    '|',
                    'indent',
                    'outdent',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'blockQuote',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'pt-br',
            licenseKey: '',
            wordCount: {
                onUpdate: stats => {
                    const charactersProgress = stats.characters / maxCharacters * circleCircumference;
                    const isLimitExceeded = stats.characters > maxCharacters;
                    const isCloseToLimit = !isLimitExceeded && stats.characters > maxCharacters * .8;
                    const circleDashArray = Math.min( charactersProgress, circleCircumference );

                    // Set the stroke of the circle to show how many characters were typed.
                    progressCircle.setAttribute( 'stroke-dasharray', `${ circleDashArray },${ circleCircumference }` );

                    // Display the number of characters in the progress chart. When the limit is exceeded,
                    // display how many characters should be removed.
                    if ( isLimitExceeded ) {
                        charactersBox.textContent = `-${ stats.characters - maxCharacters }`;
                    } else {
                        charactersBox.textContent = stats.characters;
                    }

                    // If the content length is close to the character limit, add a CSS class to warn the user.
                    container.classList.toggle( 'demo-update__limit-close', isCloseToLimit );

                    // If the character limit is exceeded, add a CSS class that makes the content's background red.
                    container.classList.toggle( 'demo-update__limit-exceeded', isLimitExceeded );

                    // If the character limit is exceeded, disable the send button.
                    sendButton.toggleAttribute( 'disabled', isLimitExceeded );
                    container.classList.toggle( 'disabled', isLimitExceeded );
                }
            }
        }),
        ClassicEditor.create(document.querySelector('#editProfileForm-description'), {
            toolbar: {
                items: [
                    '|',
                    'bold',
                    'italic',
                    'strikethrough',
                    'underline',
                    'subscript',
                    'superscript',
                    'link',
                    '|',
                    'indent',
                    'outdent',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'blockQuote',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'pt-br',
            licenseKey: '',
        }),
        ClassicEditor.create(document.querySelector('#editProfileForm-englishShortDescription'), {
            toolbar: {
                items: [
                    '|',
                    'bold',
                    'italic',
                    'strikethrough',
                    'underline',
                    'subscript',
                    'superscript',
                    'link',
                    '|',
                    'indent',
                    'outdent',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'blockQuote',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'pt-br',
            licenseKey: '',
            wordCount: {
                onUpdate: stats => {
                    const charactersProgress = stats.characters / englishMaxCharacters * englishCircleCircumference;
                    const isLimitExceeded = stats.characters > englishMaxCharacters;
                    const isCloseToLimit = !isLimitExceeded && stats.characters > englishMaxCharacters * .8;
                    const circleDashArray = Math.min( charactersProgress, englishCircleCircumference );

                    // Set the stroke of the circle to show how many characters were typed.
                    englishProgressCircle.setAttribute( 'stroke-dasharray', `${ circleDashArray },${ englishCircleCircumference }` );

                    // Display the number of characters in the progress chart. When the limit is exceeded,
                    // display how many characters should be removed.
                    if ( isLimitExceeded ) {
                        englishCharactersBox.textContent = `-${ stats.characters - englishMaxCharacters }`;
                    } else {
                        englishCharactersBox.textContent = stats.characters;
                    }

                    // If the content length is close to the character limit, add a CSS class to warn the user.
                    englishContainer.classList.toggle( 'demo-update__limit-close', isCloseToLimit );

                    // If the character limit is exceeded, add a CSS class that makes the content's background red.
                    englishContainer.classList.toggle( 'demo-update__limit-exceeded', isLimitExceeded );

                    // If the character limit is exceeded, disable the send button.
                    sendButton.toggleAttribute( 'disabled', isLimitExceeded );
                    englishContainer.classList.toggle( 'disabled', isLimitExceeded );
                }
            }
        }),
        ClassicEditor.create(document.querySelector('#editProfileForm-englishDescription'), {
            toolbar: {
                items: [
                    '|',
                    'bold',
                    'italic',
                    'strikethrough',
                    'underline',
                    'subscript',
                    'superscript',
                    'link',
                    '|',
                    'indent',
                    'outdent',
                    '|',
                    'bulletedList',
                    'numberedList',
                    '|',
                    'blockQuote',
                    '|',
                    'undo',
                    'redo'
                ]
            },
            language: 'pt-br',
            licenseKey: '',
        })
    ]).then(editors => {
        editors.map(editor => {
            window.editor = editor;
        });
    }).catch(error => {
        console.error('Oops, something went wrong!');
        console.error('Please, report the following error on https://github.com/ckeditor/ckeditor5/issues with the build id and the error stack trace:');
        console.warn('Build id: xh0zmp6752d4-vh4e9flxxbu4');
        console.error(error);
    })
</script>