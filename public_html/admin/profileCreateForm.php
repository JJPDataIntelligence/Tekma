<form name='createProfileForm' id='createProfileForm' enctype='multipart/form-data' method='POST' action='/admin/updateProfile.php'>
    <input hidden id='createProfileForm-action' name='action' value='createProfile'/>
    <div class="container-fluid jumbotron" style='padding: 15px;'>
        
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
                <button type='submit' class='btn btn-info'>Criar Perfil</button>
            </div>
        </div>

    </div>
</form>