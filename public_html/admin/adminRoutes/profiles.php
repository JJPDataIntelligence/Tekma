<?php require_once(__DIR__."/../../model/User.php");?>
<?php require_once(__DIR__."/../../model/Member.php"); ?>
<?php if (!isset($_SESSION)) session_start();?>
<?php 
    if (!isset($_SESSION["user"])) {
        session_destroy();
        header('Location: /admin/login');
    } else {
        $user = unserialize($_SESSION["user"]);
        $user->hydrateProfile();

        if (!$user->isAdmin()) {
            $_SESSION['actionError'] = "Você não tem permissão para acessar esta página !";
            header('Location: /admin');
        }
    }
?>

<?php 
    $url = $_SERVER["REQUEST_URI"];
    $fullPath = explode("/", rtrim($url, "/"));
    $lastPath = array_pop($fullPath);
    if (count($fullPath) > 2) {
        $page = intval($lastPath);
    } else {
        $page = 1;
    }
?>

<div class="dashboard-container"> 
    <?php include_once(__DIR__.'/../navbar.php'); ?>

    <div class='container'>
        <div class="jumbotron">
            <h2>Cooperados</h2><br/>
            <table class="table">
                <thead>
                    <tr>
                        <th>Apelido</th>
                        <th>Nome Completo</th>
                        <th>Usuário Vinculado</th>
                        <th class='text-center'>Ativo</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="profile-list">
                    <?php 
                        $profileList = Member::fetchMembers(5, ($page - 1) * 5);
                        foreach ($profileList as $key => $row) {
                            echo    "<tr ".($row['id'] == $user->profileID_getter() ? "class='info'" : '').">
                                        <td>".$row['apelido']."</td>
                                        <td>".$row['nome']."</td>
                                        <td>
                                            <a href='/admin/users?username=".$row['username']."' data-toggle='tooltip' data-placement='top' title='Explorar Usuário Associado'>".$row['username']."</a>
                                        </td>
                                        <td class='text-center'>".(
                                            intval($row['active']) === 1 ? 
                                            "<button class='btn btn-block btn-success btn-deactivate-profile' ".($row['id'] == $user->profileID_getter() ? "disabled" : "")." data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Desativar'><i class='fa fa-check' aria-hidden='true'></i></button>" :
                                            "<button class='btn btn-block btn-danger btn-activate-user' data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Ativar'><i class='fa fa-times' aria-hidden='true'></i></button>"
                                        )."</td>
                                        <td class='text-right'>
                                            <a class='btn btn-default' href='/admin/profile/".$row['id']."' data-toggle='tooltip' data-placement='top' title='Explorar Perfil'>
                                                <i class='fa fa-external-link' aria-hidden='true'></i>
                                            </a>
                                            <button class='btn btn-danger btn-remove-profile' ".($row['id'] == $user->profileID_getter() ? "disabled" : "")." data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Remover'>
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                    </tr>";
                        }?>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <ul class="pagination">
                                <?php $totalPages = ceil(Member::countMembers() / 5); ?>
                                <?php echo    "<li><a href='/admin/profiles'>&laquo;</a></li>";?>
                                <?php if ($page > 3 && $totalPages > 6) {
                                    echo "<li class='disabled'><a href='#'>...</a></li>";
                                }?>
                                <?php for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($totalPages > 6) {
                                        if ($i > $page - 3 && $i < $page + 3) {
                                            echo "<li ".($i == $page ? "class='active'" : "")."><a href='/admin/profiles/".$i."'>".$i."</a></li>";    
                                        }
                                    } else {
                                        echo "<li ".($i == $page ? "class='active'" : "")."><a href='/admin/profiles/".$i."'>".$i."</a></li>";
                                    }
                                }?>
                                <?php if ($page < $totalPages - 3 && $totalPages > 6) {
                                    echo "<li class='disabled'><a href='#'>...</a></li>";
                                }?>
                                <?php echo    "<li><a href='/admin/profiles/".$totalPages."'>&raquo;</a></li>";?>
                            </ul>
                        </td>
                        <td colspan='3'>
                            <form action="#" method="POST" >
                                <div class="input-group" >
                                    <input type='text' class='form-control' placeholder='Filtrar...' id='profile-search-box'/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" id="btn-search-profile" type="button">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </td>
                        <td class='text-right'>
                            <button class='btn btn-info' id='btn-bulkaddprofiles' data-toggle="tooltip" data-placement="bottom" title="Adicionar Perfis em Massa"><i class="fa fa-upload" aria-hidden="true"></i></button>
                            <button class='btn btn-info' id='btn-addprofile' data-toggle="tooltip" data-placement="bottom" title="Adicionar Perfil Único"><i class="fa fa-plus" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<!-- Bulk Insert Modal -->
<div class="modal fade" id="bulkInsertModal" tabindex="-1" role="dialog" aria-labelledby="bulkInsertModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">   
            <form method="POST" id="bulk-add-profile-form" action="/admin/profileActions.php" enctype='multipart/form-data'>
                <input hidden name="action" value="bulkCreateProfiles" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Adição Massiva de Perfis</h4>
                </div>         
                <div class="modal-body">        
                    <br/>
                    <p style='display: flex; justify-content: space-between'>
                        Utilize o template disponibilizado ao lado como modelo para a inclusão de perfis.
                        <a class='btn btn-warning' data-toggle="tooltip" data-placement="bottom" title="Baixar Template" href='/admin/resources/template-criacao-perfil.xlsx' download>
                            Template <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        </a>
                    </p>
                    <br/>
                    <label for='upload-profiles' class='btn btn-block btn-info'>
                        Selecione um arquivo <input type='file' name='profiles' id='upload-profiles' style='display: none;' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-bulk-add-profiles-submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Single Insert Modal -->
<div class="modal fade" id="insertProfileModal" tabindex="-1" role="dialog" aria-labelledby="insertProfileModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Adicionar Perfil</h4>
            </div>    


            <div class="modal-body">
                
                <form name='createProfileForm' id='createProfileForm' enctype='multipart/form-data' method='POST' action='/admin/profileActions.php'>
                    <input hidden id='createProfileForm-action' name='action' value='createProfile'/>
            
                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-fullName'>Nome Completo</label>    
                                <input type="text" name="fullName" id="createProfileForm-fullName" required class="form-control"/>
                            </div>
                        </div>

                        <div class='col-sm-3'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-nickname'>Apelido</label>    
                                <input type="text" name="nickname" id="createProfileForm-nickname" required class="form-control"/>
                            </div>
                        </div>

                        <div class='col-sm-3'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-title'>Título <i class="fa fa-info-circle" data-toggle="tooltip" title="Uma função ou título de destaque" aria-hidden="true"></i></label>
                                <input type="text" name="title" id="createProfileForm-title" class="form-control"/>
                            </div>
                        </div>
                    </div>
                    
                    <br/>

                    <div class='row'>
                        <div class='col-sm-6'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-email'>Email</label>    
                                <input type="text" name="email" id="createProfileForm-email" required class="form-control"/>
                            </div>
                        </div>

                        <div class='col-sm-6'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-linkedin'>LinkedIn</label>    
                                <input type="text" name="linkedin" id="createProfileForm-linkedin" class="form-control"/>
                            </div>
                        </div>
                    </div>

                    <br/>

                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-shortDescription'>Descrição Curta</label>
                                <textarea required name="shortDescription" maxlength="256" rows="3" id="createProfileForm-shortDescription" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <br/>

                    <div class='row'>
                        <div class='col-sm-12'>
                            <div class="input-group" style='width: 100%'>
                                <label for='createProfileForm-description'>Descrição Completa</label>
                                <textarea required name="description" rows="5" id="createProfileForm-description" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <br/>

                    <div class="row">
                        <div class='col-sm-12 text-right'>
                            <button type='submit' id="btn-cancel" class='btn btn-default'>Cancelar</button>
                            <button type='submit' class='btn btn-info'>Criar Perfil</button>
                        </div>
                    </div>  

                </form>

            </div>
        </div>
    </div>
</div>


<script>

    function createProfileRow(profile) {
        return (
            `<tr ${(profile['id'] == <?php echo $user->profileID_getter() ? $user->profileID_getter() : "null"; ?>) ? "class='info'" : ''}>
                <td>${profile['apelido']}</td>
                <td>${profile['nome']}</td>
                <td>
                    <a href='/admin/users?username=${profile['username']} data-toggle='tooltip' data-placement='top' title='Explorar Usuário Associado'>${profile['username'] ? profile['username'] : ""}</a>
                </td>
                <td class='text-center'>
                    ${parseInt(profile['active']) === 1 ? 
                        `<button class='btn btn-block btn-success btn-deactivate-profile' ${profile['id'] == <?php echo $user->profileID_getter() ? $user->profileID_getter() : "null" ?> ? "disabled" : "null"} data-id=${profile['id']} data-toggle='tooltip' data-placement='top' title='Desativar'><i class='fa fa-check' aria-hidden='true'></i></button>` :
                        `<button class='btn btn-block btn-danger btn-activate-profile' data-id=${profile['id']} data-toggle='tooltip' data-placement='top' title='Ativar'><i class='fa fa-times' aria-hidden='true'></i></button>`
                    }
                </td>
                <td class='text-right'>
                    <a class='btn btn-default' href='/admin/profile/${profile['id']}' data-toggle='tooltip' data-placement='top' title='Explorar Perfil'>
                        <i class='fa fa-external-link' aria-hidden='true'></i>
                    </a>
                    <button class='btn btn-danger btn-remove-profile' ${profile['id'] == <?php echo $user->profileID_getter() ? $user->profileID_getter() : "null" ?> ? "disabled" : ""} data-id=${profile['id']} data-toggle='tooltip' data-placement='top' title='Remover'>
                        <i class='fa fa-trash' aria-hidden='true'></i>
                    </button>
                </td>
            </tr>`
        );
    }


    $('#btn-search-profile').on('click', function (event) {
        event.preventDefault();
        let searchTerm = $('#profile-search-box').val();
        
        $.ajax({
            url:'/admin/profileActions.php',
            method:'POST',
            data: {
                "action": "searchProfiles",
                "searchTerm": searchTerm,
                "limit": 5,
                "offset": null
            },
            success: function(data) {          
                let profiles = JSON.parse(data)["profiles"];
                let profileList = profiles.map(profile => createProfileRow(profile));
                $('tbody').html(profileList.join());                    
            },
            error: function(err) {
                console.error(err);
            }
        });
    })

    $('#profile-list').on('click', '.btn-deactivate-profile', function () {
        event.preventDefault();
        let profileID = $(this).data('id');
        let $button = $(this);

        $.ajax({
            url:'/admin/profileActions.php',
            method:'POST',
            data:{                    
                "action": "deactivateProfile",
                "id": profileID
            },
            success: function(data) {                
                $button
                    .removeClass('btn-success').removeClass('btn-deactivate-profile')
                    .addClass('btn-danger').addClass('btn-activate-user');
                $button.find('i').removeClass('fa-check').addClass('fa-times');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });

    $('#profile-list').on('click', '.btn-activate-user', function () {
        event.preventDefault();
        let profileID = $(this).data('id');
        let $button = $(this);

        $.ajax({
            url:'/admin/profileActions.php',
            method:'POST',
            data:{                    
                "action": "activateProfile",
                "id": profileID
            },
            success: function(data) {
                $button
                    .removeClass('btn-danger').removeClass('btn-activate-user')
                    .addClass('btn-success').addClass('btn-deactivate-profile');
                $button.find('i').removeClass('fa-times').addClass('fa-check');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });


    $('#btn-bulk-add-profiles-submit').on('click', function () {
        $('#bulkInsertModal').modal('hide');
        $('body').addClass('loading');
        $('#bulk-add-profile-form').submit();
    })

    $('#btn-bulkaddprofiles').on('click', function () {
        $('#bulkInsertModal').modal('show');
    });

    $('#upload-profiles').on('change', function (event) {
        if (event.target.files.length === 1) {
            $(this).closest('label').removeClass('btn-info').addClass('btn-success');
            
        } else {
            $(this).closest('label').removeClass('btn-success').addClass('btn-info');
            
        }
    })

    $('#profile-list').on('click', '.btn-remove-profile', function (event) {
        event.preventDefault();
        let profileID = $(this).data('id');
        let row = $(this).closest('tr');

        $.ajax({
            url:'/admin/profileActions.php',
            method:'POST',
            data:{                    
                "action": "removeProfile",
                "id": profileID
            },
            success: function(data) {                
                row.fadeOut(300, function() {
                    $(this).remove();
                });     
            },
            error: function(err) {
                console.error(err);
            }
        });

    })

    $('#btn-save-user').on('click', function (event){
        event.preventDefault();
        
        let username = $('username');
        let password = $(".pwd")
        let passwordConfirmation = $(".pwd-confirm");

        if (password.val() !== passwordConfirmation.val()) {
            $('.pwd-group').addClass('has-error');
            jSuites.notification({
                error: 1,
                name: 'A confirmação de Senha e a Senha Diferem.',
            });
        } else if (username.val() === "" || password.val() === "" || passwordConfirmation.val() === "") {
            jSuites.notification({
                error: 1,
                name: 'Os Campos de Nome de Usuário e Senha São Obrigatórios.',
            });
        } else {
            $('#create-user-form').submit();
        }

    })

    $('#btn-addprofile').on('click', function (){
        $('#insertProfileModal').modal('show');
    });

    $('#btn-cancel').on('click', function (event){
        event.preventDefault();
        $('#insertProfileModal').modal('hide');
    });

    $(".reveal-pwd").on('click',function() {
        var $pwd = $(".pwd");
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
            $(".reveal-pwd").find('.fa').first().removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $pwd.attr('type', 'password');
            $(".reveal-pwd").find('.fa').first().removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    $(".reveal-pwd-confirm").on('click',function() {
        var $pwd = $(".pwd-confirm");
        if ($pwd.attr('type') === 'password') {
            $pwd.attr('type', 'text');
            $(".reveal-pwd-confirm").find('.fa').first().removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            $pwd.attr('type', 'password');
            $(".reveal-pwd-confirm").find('.fa').first().removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });


    <?php if (isset($_SESSION['actionError'])) {
        echo    "jSuites.notification({
            name: 'Erro ao Executar a Ação Desejada',
            error: 1,
            message: '".$_SESSION['actionError']."'
        })"; 
        unset($_SESSION['actionError']);
    }?>
    <?php if (isset($_SESSION['actionSuccess'])) {
        echo    "jSuites.notification({
            name: '".$_SESSION['actionSuccess']."',
        })"; 
        unset($_SESSION['actionSuccess']);
    }?>

</script>

