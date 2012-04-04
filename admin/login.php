<form action="index.php" method="post" class='cmxform form-horizontal login'>
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
	</div>


</form>
