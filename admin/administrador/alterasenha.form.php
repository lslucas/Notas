<?php
 if (isset($_POST) && !empty($_POST)) {
  include_once 'alterasenha.mod.exec.php';
 }

?>
<div class='error container-error'><div class='error-icon'>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:</div>
	<ol> 
		<li><label for="senha_atual" class="error-validate">Para sua segurança, digite a sua senha atual</label></li> 
		<li><label for="senha" class="error-validate">Informe a nova senha</label></li> 
		<li><label for="confirma_senha" class="error-validate">O campo "confirma senha" deve ser idêntico ao campo "senha"</label></li> 
	</ol> 
  </div>




<form method='post' action='?p=<?=$p?>&alterasenha' class='form cmxform'>
 <input type='hidden' name='item' value='<?=$_SESSION['user']['id']?>'>


<h1>Alterar senha</h1>
<p class='header'>Preencha todos os campos abaixo para alterar sua senha. Após o preenchimento você receberá um e-mail com sua nova senha e o seu login.</p>


  <ol>

	<li>
	  <label>Senha atual<span class='small'>Por segurança, informe a senha atual</span></label>
	  <input type='password' name='senha_atual' id='senha_atual' class='required'>
	  <br>&nbsp;
	</li>

	<li>
	  <label>Nova senha<span class='small'>Sua nova senha</span></label>
	  <input type='password' name='senha' id='senha' class='required'>
	</li>

	<li>
	  <label>Confirme a senha<span class='small'>Confirme sua nova senha</span></label>
	  <input type='password' name='confirma_senha' id='confirma_senha'>
	</li>

  </ol>

    <br>
    <p align='center'>
    <input type='submit' value='ok' class='first'><input type='button' id='form-back' value='voltar'></p>
    <div class='spacer'></div>


</form>


