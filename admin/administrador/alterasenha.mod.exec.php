<?php
  
  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }


 if(empty($res['senha']) || empty($res['confirma_senha']) || empty($res['senha_atual'])) {
  echo '<div class="error"><div class="error-icon">Preencha <b>todos</b> os campos antes de avançar!</div></div>';
  echo '<div class="spacer">&nbsp;</div>';


 } else {

   if (md5($res['senha_atual'])==$_SESSION['user']['senha'] && $res['senha']==$res['confirma_senha']) {

     $res['nova_senha']=md5($res['senha']);
     $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET
  		  ${var['pre']}_senha=?";
     $sql.=" WHERE ${var['pre']}_id=?";

     if ($qry=$conn->prepare($sql)) {
	$qry->bind_param('si', $res['nova_senha'],$res['item']); 
	$qry->execute();


	   if ($qry==false) echo 'Ocorreu algum erro!';
	    else {

	     # define nome e email para enviar ao include de email
	     $res['email'] = $_SESSION['user']['email'];
	     $res['nome']  = $_SESSION['user']['nome'];

	     echo '<div class="success"><div class="success-icon">Sua senha foi alterada!</div></div>';
      	     echo '<div class="spacer">&nbsp;</div>';
	     include_once 'inc.email.php';
	    } 


	$qry->close();

     }




    } else {
      echo '<div class="error"><div class="error-icon">Sua atual não confere! Tente novamente.</div></div>';
      echo '<div class="spacer">&nbsp;</div>';

    }

}

?>
