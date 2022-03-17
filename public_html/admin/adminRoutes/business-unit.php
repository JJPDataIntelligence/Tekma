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
        if (!$user->isAdmin()) {
            header('Location: /admin');
            die();
        }
    }
?>

<?php 
    $businessUnitID = array_pop(explode("/", $_SERVER["REQUEST_URI"]));
    $businessUnit = new BusinessUnit($businessUnitID, 'pt', TRUE, TRUE, TRUE, TRUE);
?>

<?php function makeShownName($fullName, $nickname) {
    $pos = strpos($fullName, $nickname);
    if ($pos !== false) {
        return substr_replace($fullName, "<span class='orange'>".$nickname."</span>", $pos, strlen($nickname));
    } else {
        return $fullName." <span class='orange'>\"".$nickname."\"</span>";
    }
}?>

<div class="dashboard-container">
    <script src="/js/jquery.bootstrap-duallistbox.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/css/bootstrap-duallistbox.min.css"> 
    
    <?php include_once(__DIR__.'/../navbar.php'); ?>
    
    <div class="container dashboard-body">
        <div class="container-fluid jumbotron">
            <div class='page-header'>
                <h3 class='clearfix'>
                    <?php echo $businessUnit->name_getter();?>
                    <?php if(!$businessUnit->hasParent()){ ?>
                        <span class='badge pull-right progress-bar-success'>BU BASE</span>
                    <?php } else { ?>
                        <span class='badge pull-right progress-bar-success'>SUBUNIDADE</span>
                    <?php } ?>
                    <?php if($businessUnit->hasChildren()){ ?>
                        <span class='badge pull-right progress-bar-success' style='margin-right: 10px;'>POSSUI SUBUNIDADES</span>
                    <?php } ?> 
                </h3>
            </div>
            
            <div class="profile-body">
                    
                <?php if ($businessUnit->head_getter()) { ?>    
                    <h2><i class="fa fa-user" aria-hidden="true"></i><small>
                        <a style='color: inherit' href="/admin/profile/<?php echo $businessUnit->head_getter()->id_getter(); ?>">
                            <?php echo trim(makeShownName($businessUnit->head_getter()->name_getter(), $businessUnit->head_getter()->nickname_getter())); ?>
                        </a>
                    </small></h2>
                
                <?php } else { ?>
                    <div class="alert alert-warning" role="alert">Unidade de Negócio sem Responsável Associado !</div>
                <?php } ?>
                    
                <br/>

                <div class='row'>
                    <blockquote class='clearfix' id="portugueseDescription">
                        <footer><b>Descrição Longa</b><span class='pull-right'>🇧🇷<span></footer>
                        <?php echo $businessUnit->portugueseDescription_getter();?>
                    </blockquote>
                </div>

                <div class='row'>
                    <blockquote class='clearfix' id="portugueseShortDescription">
                        <footer><b>Descrição Curta</b><span class='pull-right'>🇧🇷<span></footer>
                        <?php echo $businessUnit->portugueseShortDescription_getter();?>
                    </blockquote>
                </div>

                <div class='row'>
                    <blockquote class='clearfix' id="englishDescription">
                        <footer><b>Descrição Longa</b><span class='pull-right'>🇺🇸<span></footer>
                        <?php echo $businessUnit->englishDescription_getter();?>
                    </blockquote>
                </div>

                <div class='row'>
                    <blockquote class='clearfix' id="englishShortDescription">
                        <footer><b>Descrição Curta</b><span class='pull-right'>🇺🇸<span></footer>
                        <?php echo $businessUnit->englishShortDescription_getter();?>
                    </blockquote>
                </div>

            </div>

            <br/>
            <div class='row clearfix'>
                <div class='pull-left'>
                    <button class='btn btn-warning' data-toggle="modal" data-target="#head-modal">Alterar Responsável</button>
                </div>
                <div class='pull-right'>
                    <button class='btn btn-info' data-toggle="modal" data-target="#subunits-modal">Administrar Subunidades</button>
                    <button class='btn btn-info' data-toggle="modal" data-target="#members-modal">Administrar Cooperados Associados</button>
                    <a class='btn btn-info' href='/admin/edit-bu/<?php echo $businessUnit->id_getter(); ?>'>Editar Unidade de Negócio</a>
                </div>
            </div>

        </div>

        <!-- Subunits Modal -->
        <div class="modal fade" id="subunits-modal" tabindex="-1" role="dialog" aria-labelledby="subunits-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Subunidades</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/admin/buActions.php" id="subunit-form">
                            <input type='hidden' name="action" value='associateBUS' />
                            <input type='hidden' name="parent_id" value='<?php echo $businessUnit->id_getter() ?>' />
                            <select name="bus[]" id="bus" multiple>
                                <?php foreach($businessUnit->fetchChildren() as $key => $bu) { ?>
                                    <option value="<?php echo $bu['id']?>" selected="selected" ><?php echo $bu['name']?></option>
                                <?php }?>
                                <?php foreach($businessUnit->fetchOrphanBUS() as $key => $bu) { ?>
                                    <option value="<?php echo $bu['id']?>" ><?php echo $bu['name']?></option>
                                <?php }?>
                            </select>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="subunit-confirm" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Members Modal -->
        <div class="modal fade" id="members-modal" tabindex="-1" role="dialog" aria-labelledby="members-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Membros</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/admin/buActions.php" id="members-form">
                            <input type='hidden' name="action" value='associateMembers' />
                            <input type='hidden' name="bu_id" value='<?php echo $businessUnit->id_getter() ?>' />
                            <select name="members[]" id="members" multiple>
                                <?php foreach($businessUnit->members_getter() as $key => $member) { ?>
                                    <option value="<?php echo $member->id_getter(); ?>" selected="selected" >
                                        <?php echo $member->name_getter(); ?>
                                    </option>
                                <?php } ?>
                                <?php foreach($businessUnit->fetchNonMembers() as $key => $nonMember) { ?>
                                    <option value="<?php echo $nonMember['id']; ?>">
                                        <?php echo $nonMember['nome']; ?>
                                    </option>
                                <?php }?>
                            </select>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="member-confirm" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Head Modal -->
        <div class="modal fade" id="head-modal" tabindex="-1" role="dialog" aria-labelledby="head-modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Responsável</h4>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="/admin/buActions.php" id="head-form">
                            <input type='hidden' name="action" value='associateHead' />
                            <input type='hidden' name="bu_id" value='<?php echo $businessUnit->id_getter() ?>' />
                            <input hidden id='profile' name='profile' />
                            <label for='profile'>Perfis Disponíveis</label>
		                    <div id='profileSelector' name='profileSelector' placeholder='Perfil' style='width: 100%'></div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" id="head-confirm" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>


        <script>

            $('#subunit-confirm').on('click', () => {
                $('#subunit-form').submit()
            });

            $('#member-confirm').on('click', () => {
                $('#members-form').submit()
            });

            $('#head-confirm').on('click', () => {
                $('#head-form').submit()
            });

            $(document).ready(function() {
                jSuites.dropdown(document.getElementById('profileSelector'), {
                    url: '/admin/fetch/cooperative-partners',
                    autocomplete: true,
                    lazyLoading: true,
                    onchange: function (element, index, oldValue, newValue, oldLabel, newLabel) {
                        document.getElementById('profile').value = newValue;
                    },
                    value: '<?php echo $businessUnit->head_getter() !== null ? $businessUnit->head_getter()->id_getter() : "" ?>'
                })

                $('select[name="members[]"]').bootstrapDualListbox({
                    nonSelectedListLabel: 'Não Membros',
                    selectedListLabel: 'Membros',
                    moveOnSelect: false,
                });
                
                $('select[name="bus[]"]').bootstrapDualListbox({
                    nonSelectedListLabel: 'Não Vinculadas',
                    selectedListLabel: 'Vinculadas',                    
                    moveOnSelect: false,
                });
            });
        </script>
    </div>

    <script>
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