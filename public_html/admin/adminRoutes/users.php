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
            <h2>Usuários</h2><br/>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome de Usuário</th>
                        <th>Perfil Associado</th>
                        <th class='text-center'>Ativo</th>
                        <th class='text-center'>Administrador</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="user-list">
                    <?php 
                        $userList = User::fetchUsers(5, ($page - 1) * 5);
                        foreach ($userList as $key => $row) {
                            echo    "<tr ".($row['id'] == $user->id_getter() ? "class='info'" : '').">
                                        <td>".$row['username']."</td>
                                        <td>
                                            <a href='/admin/profiles?id=".$row['profile']."' data-toggle='tooltip' data-placement='top' title='Explorar Perfil Associado'>".$row['nome']."</a>
                                        </td>
                                        <td class='text-center'>".(
                                            intval($row['active']) === 1 ? 
                                            "<button class='btn btn-block btn-success btn-deactivate-user' ".($row['id'] == $user->id_getter() ? "disabled" : "")." data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Desativar'><i class='fa fa-check' aria-hidden='true'></i></button>" :
                                            "<button class='btn btn-block btn-danger btn-activate-user' data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Ativar'><i class='fa fa-times' aria-hidden='true'></i></button>"
                                        )."</td>
                                        <td class='text-center'>".(
                                            intval($row['admin']) === 1 ? 
                                            "<button class='btn btn-block btn-success btn-deactivate-admin' ".($row['id'] == $user->id_getter() ? "disabled" : "")." data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Remover Administrador'><i class='fa fa-check' aria-hidden='true'></i></button>" : 
                                            "<button class='btn btn-block btn-danger btn-activate-admin' data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Adicionar Administrador'><i class='fa fa-times' aria-hidden='true'></i></button>"
                                        )."</td>
                                        <td class='text-right'>
                                            <button class='btn btn-danger btn-remove-user' ".($row['id'] == $user->id_getter() ? "disabled" : "")." data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Remover'>
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                    </tr>";
                        }?>
                        <tr id='row-adduser' style='display: none;'>
                            <form method="POST" action="/admin/userActions.php" id="create-user-form">
                                <input type='hidden' name="action" value='createUser' />
                                <td class='text-center'>
                                    <input class='form-control' name='username' placeholder='Nome de Usuário' type='text' required/>
                                </td>
                                <td class='text-center'>

                                    <div class="input-group pwd-group" style='margin-bottom: 5px;'>
                                        <input type="password" class="form-control pwd" placeholder='Senha' name='password' required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default reveal-pwd" type="button"><i class="fa fa-eye" aria-hidden="true"></i></i></button>
                                        </span>
                                    </div>

                                    <div class="input-group pwd-group" style='margin-bottom: 5px;'>
                                        <input type="password" class="form-control pwd-confirm" placeholder='Confirmação de Senha' name='passwordConfirmation' required>
                                        <span class="input-group-btn">
                                            <button class="btn btn-default reveal-pwd-confirm" type="button"><i class="fa fa-eye" aria-hidden="true"></i></button>
                                        </span>
                                    </div>

                                </td>
                                <td class='text-center'>
                                    <div class='checkbox-inline'>
                                        <label><input name='ativo' type='checkbox'/>Ativo</label>
                                    </div>
                                </td>
                                <td class='text-center'>
                                    <div class='checkbox-inline'>
                                        <label><input name='admin' type='checkbox'/>Administrador</label>
                                    </div>
                                </td>
                                <td class='text-right'>
                                    <button class='btn btn-danger' id="btn-cancel" data-toggle="tooltip" data-placement="top" title="Cancelar">
                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                    </button>
                                    <button class='btn btn-success' type='submit' data-toggle="tooltip" data-placement="top" title="Salvar" id='btn-save-user'>
                                        <i class="fa fa-check" aria-hidden="true"></i>
                                    </button>
                                </td>
                            </form>
                        </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <ul class="pagination">
                                <?php $totalPages = ceil(User::countUsers() / 5); ?>
                                <?php echo    "<li><a href='/admin/users'>&laquo;</a></li>";?>
                                <?php if ($page > 3 && $totalPages > 6) {
                                    echo "<li class='disabled'><a href='#'>...</a></li>";
                                }?>
                                <?php for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($totalPages > 6) {
                                        if ($i > $page - 3 && $i < $page + 3) {
                                            echo "<li ".($i == $page ? "class='active'" : "")."><a href='/admin/users/".$i."'>".$i."</a></li>";    
                                        }
                                    } else {
                                        echo "<li ".($i == $page ? "class='active'" : "")."><a href='/admin/users/".$i."'>".$i."</a></li>";
                                    }
                                }?>
                                <?php if ($page < $totalPages - 3 && $totalPages > 6) {
                                    echo "<li class='disabled'><a href='#'>...</a></li>";
                                }?>
                                <?php echo    "<li><a href='/admin/users/".$totalPages."'>&raquo;</a></li>";?>
                            </ul>
                        </td>
                        <td colspan='3'>
                            <form action="/a" method="POST" >
                                <div class="input-group" >
                                    <input type='text' class='form-control' placeholder='Filtrar...' id='user-search-box'/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" id="btn-search-user" type="button">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </td>
                        <td class='text-right'>
                            <button class='btn btn-info' id='btn-bulkadduser' data-toggle="tooltip" data-placement="bottom" title="Adicionar Usuários em Massa"><i class="fa fa-upload" aria-hidden="true"></i></button>
                            <button class='btn btn-info' id='btn-adduser' data-toggle="tooltip" data-placement="bottom" title="Adicionar Usuário Único"><i class="fa fa-plus" aria-hidden="true"></i></button>
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
            <form method="POST" id="bulk-add-user-form" action="/admin/userActions.php" enctype='multipart/form-data'>
                <input hidden name="action" value="bulkCreateUsers" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Adição Massiva de Usuário</h4>
                </div>         
                <div class="modal-body">        
                    <br/>
                    <p style='display: flex; justify-content: space-between'>
                        Utilize o template disponibilizado ao lado como modelo para a inclusão de usuários.
                        <a class='btn btn-warning' data-toggle="tooltip" data-placement="bottom" title="Baixar Template" href='/admin/resources/template-criacao-usuario.xlsx' download>
                            Template <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        </a>
                    </p>
                    <br/>
                    <label for='upload-users' class='btn btn-block btn-info'>
                        Selecione um arquivo <input type='file' name='users' id='upload-users' style='display: none;' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>                
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-bulk-add-users-submit" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>

    function createUserRow(user) {
        return (
            `<tr ${user['id'] === <?php echo $user->id_getter();?> ? "class='info'" : ""}>
                <td>
                    ${user['username']}
                </td>
                <td>
                    ${user['profile'] ? `<a href='/admin/profiles?id=${user['profile']}' data-toggle='tooltip' data-placement='top' title='Explorar Perfil Associado'>${user['nome']}</a>` : ''}
                </td>
                <td class='text-center'>
                ${user['active'] === 1 ? 
                    `<button class='btn btn-block btn-success btn-deactivate-user' ${user['id'] == <?php echo $user->id_getter() ?> ? "disabled" : ""} data-id=${user['id']} data-toggle='tooltip' data-placement='top' title='Desativar'><i class='fa fa-check' aria-hidden='true'></i></button>` :
                    `<button class='btn btn-block btn-danger btn-activate-user' data-id=${user['id']} data-toggle='tooltip' data-placement='top' title='Ativar'><i class='fa fa-times' aria-hidden='true'></i></button>`}
                </td>
                <td class='text-center'>
                ${user['admin'] === 1 ? 
                    `<button class='btn btn-block btn-success btn-deactivate-admin' ${user['id'] == <?php echo $user->id_getter() ?> ? "disabled" : ""} data-id=${user['id']} data-toggle='tooltip' data-placement='top' title='Remover Administrador'><i class='fa fa-check' aria-hidden='true'></i></button>` :
                    `<button class='btn btn-block btn-danger btn-activate-admin' data-id=${user['id']} data-toggle='tooltip' data-placement='top' title='Adicionar Administrador'><i class='fa fa-times' aria-hidden='true'></i></button>`}
                </td>
                <td class='text-right'>
                    <button class='btn btn-danger btn-remove-user' ${user['id'] == <?php echo $user->id_getter() ?> ? "disabled" : ""} data-id=${user['id']} data-toggle='tooltip' data-placement='top' title='Remover'>
                        <i class='fa fa-trash' aria-hidden='true'></i>
                    </button>
                </td>
            </tr>`
        );
    }


    $('#btn-search-user').on('click', function (event) {
        event.preventDefault();
        let searchTerm = $('#user-search-box').val();
        
        $.ajax({
            url:'/admin/userActions.php',
            method:'POST',
            data: {
                "action": "searchUsers",
                "searchTerm": searchTerm,
                "limit": 5,
                "offset": null
            },
            success: function(data) {          
                let users = JSON.parse(data)["users"];
                let userList = users.map(user => createUserRow(user));
                $('tbody').html(userList.join());                    
            },
            error: function(err) {
                console.error(err);
            }
        });
    })

    $('#user-list').on('click', '.btn-deactivate-user', function () {
        event.preventDefault();
        let userID = $(this).data('id');
        let $button = $(this);

        $.ajax({
            url:'/admin/userActions.php',
            method:'POST',
            data:{                    
                "action": "deactivateUser",
                "id": userID
            },
            success: function(data) {                
                $button
                    .removeClass('btn-success').removeClass('btn-deactivate-user')
                    .addClass('btn-danger').addClass('btn-activate-user');
                $button.find('i').removeClass('fa-check').addClass('fa-times');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });

    $('#user-list').on('click', '.btn-activate-user', function () {
        event.preventDefault();
        let userID = $(this).data('id');
        let $button = $(this);

        $.ajax({
            url:'/admin/userActions.php',
            method:'POST',
            data:{                    
                "action": "activateUser",
                "id": userID
            },
            success: function(data) {
                $button
                    .removeClass('btn-danger').removeClass('btn-activate-user')
                    .addClass('btn-success').addClass('btn-deactivate-user');
                $button.find('i').removeClass('fa-times').addClass('fa-check');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });

    $('#user-list').on('click', '.btn-deactivate-admin', function () {
        event.preventDefault();
        let userID = $(this).data('id');
        let $button = $(this);
        let currentUserID = <?php echo $user->id_getter(); ?>

        if (currentUserID == userID) return;

        $.ajax({
            url:'/admin/userActions.php',
            method:'POST',
            data:{                    
                "action": "deactivateUserAdmin",
                "id": userID
            },
            success: function(data) {                
                $button
                    .removeClass('btn-success').removeClass('btn-deactivate-admin')
                    .addClass('btn-danger').addClass('btn-activate-admin');
                $button.find('i').removeClass('fa-check').addClass('fa-times');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });

    $('#user-list').on('click', '.btn-activate-admin', function () {
        event.preventDefault();
        let userID = $(this).data('id');
        let $button = $(this);

        $.ajax({
            url:'/admin/userActions.php',
            method:'POST',
            data:{                    
                "action": "activateUserAdmin",
                "id": userID
            },
            success: function(data) {
                $button
                    .removeClass('btn-danger').removeClass('btn-activate-admin')
                    .addClass('btn-success').addClass('btn-deactivate-admin');
                $button.find('i').removeClass('fa-times').addClass('fa-check');
            },
            error: function(err) {
                console.error(err);
            }
        });
    });


    $('#btn-bulk-add-users-submit').on('click', function () {
        $('#bulkInsertModal').modal('hide');
        $('body').addClass('loading');
        $('#bulk-add-user-form').submit();
    })

    $('#btn-bulkadduser').on('click', function () {
        $('#bulkInsertModal').modal('show');
    });

    $('#upload-users').on('change', function (event) {
        if (event.target.files.length === 1) {
            $(this).closest('label').removeClass('btn-info').addClass('btn-success');
            
        } else {
            $(this).closest('label').removeClass('btn-success').addClass('btn-info');
            
        }
    })

    $('#user-list').on('click', '.btn-remove-user', function (event) {
        event.preventDefault();
        let userID = $(this).data('id');
        let row = $(this).closest('tr');

        $.ajax({
            url:'/admin/userActions.php',
            method:'POST',
            data:{                    
                "action": "removeUser",
                "id": userID
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

    $('#btn-adduser').on('click', function (){
        $('#row-adduser').fadeIn();
        $('#btn-adduser').addClass('disabled');
    });

    $('#btn-cancel').on('click', function (event){
        event.preventDefault();
        $('#row-adduser').fadeOut();
        $('#btn-adduser').removeClass('disabled');
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

