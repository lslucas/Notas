<?php
 $noFooter=1;
 #mostra msg caso haja
 if (isset($msgNotice) && !empty($msgNotice)) {
	 echo "
		 <div class='alert alert-error'>
			<a class='close' data-dismiss='alert'>×</a>
			$msgNotice
		</div>
		 ";
 }

?>
<style type='text/css'>
	body { background-color:#d9d9d9; }
</style>
<center>
	<form action="index.php" method="post" class='cmxform form-inline login' style='width:500px; text-align: left; '>
	<div class='well'>
			<img src='<?=SITE_URL?>/images/treinamentoeacao.png' width=170 border=0 style='float: left; margin-right:10px; margin-top: 40px;'/>
			<!--<img src="http://placehold.it/160x180" alt="Logo" style='float: left; margin-right:20px;'>-->
		  <fieldset>
			<!--<legend>Legend text</legend>-->
			<div class="control-group">
			  <label class="control-label" for="username">Email</label>
			  <div class="controls">
				<input type="text" class="input-xlarge email required" placeholder='email@provedor.com.br' name='username' id='username'>
			  </div>
			</div>

			<div class="control-group">
			  <label class="control-label" for="password">Senha</label>
			  <div class="controls">
				<input type="password" class="input-xlarge required" name='password' id='password'>
			  </div>
			</div>


			<div class='form-actions'>
				<input type='submit' value='Login' class='btn btn-primary'>
				<a href='esqueci-senha.php' value='Esqueci minha senha' class='btn'>Esqueci a senha</a>
			</div>
	</div>
	</form>
</center>
