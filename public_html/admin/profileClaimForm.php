<div class='row'>
	<div class='col-xs-9'>
		<label for='profile'>Perfis Disponíveis</label>
		<div id='profileSelector' name='profileSelector' placeholder='Perfil' style='width: 100%'></div>
	</div>
	<div class='col-xs-3 text-right mb-0'>
		<form name='profileForm' id='profileForm' enctype='multipart/form-data' method='POST' action='/admin/updateProfile.php'>
			<input hidden id='action' name='action' value='claimProfile' />
			<input hidden id='profile' name='profile' />
			<button type='submit' class='btn btn-info'>Selecionar</button>
		</form>
	</div>
</div>