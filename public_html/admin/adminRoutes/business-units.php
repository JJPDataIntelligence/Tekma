<?php require_once(__DIR__."/../../model/User.php");?>
<?php require_once(__DIR__."/../../model/Member.php"); ?>
<?php require_once(__DIR__."/../../model/BusinessUnit.php"); ?>
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
            <h3>Unidades de Negócio</h3><br/>
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Responsável</th>
                        <th>Pai</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="bu-list">
                    <?php 
                        $BUSList = BusinessUnit::fetchBUS(5, ($page - 1) * 5);                        
                        foreach ($BUSList as $key => $row) {
                            
                            echo    "<tr>
                                        <td>".$row['name']."</td>
                                        <td>
                                            <a href='/admin/profile/".$row['headID']."' data-toggle='tooltip' data-placement='top' title='Explorar Perfil Associado'>".$row['head']."</a>
                                        </td>
                                        <td>
                                            <a href='/admin/business-unit/".$row['parent_id']."' data-toggle='tooltip' data-placement='top' title='Explorar Unidade de Negócio Associada'>".$row['parent']."</a>
                                        </td>
                                        <td class='text-right'>
                                            <a class='btn btn-default' href='/admin/business-unit/".$row['id']."' data-toggle='tooltip' data-placement='top' title='Explorar Unidade de Negócio'>
                                                <i class='fa fa-external-link' aria-hidden='true'></i>
                                            </a>
                                            <button class='btn btn-danger btn-remove-bu' data-id=".$row['id']." data-toggle='tooltip' data-placement='top' title='Remover'>
                                                <i class='fa fa-trash' aria-hidden='true'></i>
                                            </button>
                                        </td>
                                    </tr>";
                        }?> 
                        <tr id='row-addbu' style='display: none;'>
                            <form method="POST" action="/admin/buActions.php" id="create-bu-form">
                                <input type='hidden' name="action" value='createBU' />
                                
                                <td colspan='3' class='text-center'>
                                    <input class='form-control' name='name' placeholder='Nome da Unidade de Negócio' type='text' required/>
                                </td>
                                
                                <td class='text-right'>
                                    <button class='btn btn-danger' id="btn-cancel" data-toggle="tooltip" data-placement="top" title="Cancelar">
                                        <i class="fa fa-ban" aria-hidden="true"></i>
                                    </button>
                                    <button class='btn btn-success' type='submit' data-toggle="tooltip" data-placement="top" title="Salvar" id='btn-save-bu'>
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
                                <?php $totalPages = ceil(BusinessUnit::countBUS() / 5); ?>
                                <?php echo    "<li><a href='/admin/business-units'>&laquo;</a></li>";?>
                                <?php if ($page > 3 && $totalPages > 6) {
                                    echo "<li class='disabled'><a href='#'>...</a></li>";
                                }?>
                                <?php for ($i = 1; $i <= $totalPages; $i++) {
                                    if ($totalPages > 6) {
                                        if ($i > $page - 3 && $i < $page + 3) {
                                            echo "<li ".($i == $page ? "class='active'" : "")."><a href='/admin/business-units/".$i."'>".$i."</a></li>";    
                                        }
                                    } else {
                                        echo "<li ".($i == $page ? "class='active'" : "")."><a href='/admin/business-units/".$i."'>".$i."</a></li>";
                                    }
                                }?>
                                <?php if ($page < $totalPages - 3 && $totalPages > 6) {
                                    echo "<li class='disabled'><a href='#'>...</a></li>";
                                }?>
                                <?php echo    "<li><a href='/admin/business-units/".$totalPages."'>&raquo;</a></li>";?>
                            </ul>
                        </td>
                        <td colspan='2'>
                            <form method="POST">
                                <div class="input-group" >
                                    <input type='text' class='form-control' placeholder='Filtrar...' id='bu-search-box'/>
                                    <span class="input-group-btn">
                                        <button class="btn btn-default" id="btn-search-bu" type="button">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                        </button>
                                    </span>
                                </div>
                            </form>
                        </td>
                        <td class='text-right'>
                            <button class='btn btn-info' id='btn-bulkaddbu' data-toggle="tooltip" data-placement="bottom" title="Adicionar Unidades de Negócio em Massa"><i class="fa fa-upload" aria-hidden="true"></i></button>
                            <button class='btn btn-info' id='btn-addbu' data-toggle="tooltip" data-placement="bottom" title="Adicionar Unidade de Negócio Única"><i class="fa fa-plus" aria-hidden="true"></i></button>
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
            <form method="POST" id="bulk-add-bu-form" action="/admin/buActions.php" enctype='multipart/form-data'>
                <input hidden name="action" value="bulkCreateBUS" />
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Adição Massiva de Unidades de Negócio</h4>
                </div>         
                <div class="modal-body">        
                    <br/>
                    <p style='display: flex; justify-content: space-between'>
                        Utilize o template disponibilizado ao lado como modelo para a inclusão de Unidades de Negócio.
                        <a class='btn btn-warning' data-toggle="tooltip" data-placement="bottom" title="Baixar Template" href='/admin/resources/template-criacao-unidade-de-negocio.xlsx' download>
                            Template <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                        </a>
                    </p>
                    <br/>
                    <label for='upload-bus' class='btn btn-block btn-info'>
                        Selecione um arquivo <input type='file' name='bus' id='upload-bus' style='display: none;' accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" id="btn-bulk-add-bus" class="btn btn-primary">Confirmar</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>

    function createBURow(bu) {
        return (
            `<tr>
                <td>${bu['name']}</td>
                <td>
                    <a href='/admin/profile/${bu['headID'] ? bu['headID'] : ''}' data-toggle='tooltip' data-placement='top' title='Explorar Perfil Associado'>${bu['head'] ? bu['head'] : ''}</a>
                </td>
                <td>
                    <a href='/admin/business-unit/${bu['parent_id'] ? bu['parent_id'] : ''}' data-toggle='tooltip' data-placement='top' title='Explorar Unidade de Negócio Associada'>${bu['parent'] ? bu['parent'] : ''}</a>
                </td>
                <td class='text-right'>
                    <a class='btn btn-default' href='/admin/business-unit/${bu['id']}' data-toggle='tooltip' data-placement='top' title='Explorar Unidade de Negócio'>
                        <i class='fa fa-external-link' aria-hidden='true'></i>
                    </a>
                    <button class='btn btn-danger btn-remove-bu' data-id=${bu['id']} data-toggle='tooltip' data-placement='top' title='Remover'>
                        <i class='fa fa-trash' aria-hidden='true'></i>
                    </button>
                </td>
            </tr>`
        );
    }


    $('#btn-search-bu').on('click', function (event) {
        event.preventDefault();
        let searchTerm = $('#bu-search-box').val();
        
        $.ajax({
            url:'/admin/buActions.php',
            method:'POST',
            data: {
                "action": "searchBUS",
                "searchTerm": searchTerm,
                "limit": 5,
                "offset": null
            },
            success: function(data) {          
                let businessUnits = JSON.parse(data)["businessUnits"];
                let businessUnitList = businessUnits.map(bu => createBURow(bu));
                $('tbody').html(businessUnitList.join());                    
            },
            error: function(err) {
                console.error(err);
            }
        });
    })


    $('#btn-bulk-add-bus').on('click', function () {
        $('#bulkInsertModal').modal('hide');
        $('body').addClass('loading');
        $('#bulk-add-bu-form').submit();
    })

    $('#btn-bulkaddbu').on('click', function () {
        $('#bulkInsertModal').modal('show');
    });

    $('#upload-bus').on('change', function (event) {
        if (event.target.files.length === 1) {
            $(this).closest('label').removeClass('btn-info').addClass('btn-success');
            
        } else {
            $(this).closest('label').removeClass('btn-success').addClass('btn-info');
            
        }
    })

    $('#bu-list').on('click', '.btn-remove-bu', function (event) {
        event.preventDefault();
        let businessUnitID = $(this).data('id');
        let row = $(this).closest('tr');

        $.ajax({
            url:'/admin/buActions.php',
            method:'POST',
            data:{                    
                "action": "removeBU",
                "id": businessUnitID
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

    $('#btn-addbu').on('click', function (){
        $('#row-addbu').fadeIn();
        $('#btn-addbu').addClass('disabled');
    });

    $('#btn-cancel').on('click', function (event){
        event.preventDefault();
        $('#row-addbu').fadeOut();
        $('#btn-addbu').removeClass('disabled');
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

