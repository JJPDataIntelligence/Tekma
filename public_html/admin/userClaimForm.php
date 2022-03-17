<div class='row'>
	<div class='col-xs-9'>
		<label for='profile'>Usuários Disponíveis</label>
        <div id='userSelector' name='userSelector' placeholder='Usuário' style='width: 100%'></div>
	</div>
	<div class='col-xs-3 text-right mb-0'>
		<form name='userForm' id='userForm' enctype='multipart/form-data' method='POST' action='/admin/updateProfile.php'>
			<input hidden id='action' name='action' value='claimUser' />
			<input hidden id='user' name='user' />
            <input hidden id='profile' name='profile' value=<?php echo $profile->id_getter(); ?> />
			<button type='submit' class='btn btn-info'>Selecionar</button>
		</form>
	</div>
</div>