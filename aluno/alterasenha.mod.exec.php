<?php
  
  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }


if(empty($res['senha']) || empty($res['confirma_senha']) || empty($res['senha_atual']))
	echo divAlert('Preencha <b>todos</b> os campos antes de avançar!');

else {

	if (md5($res['senha_atual'])==$_SESSION['user']['senha'] && $res['senha']==$res['confirma_senha']) {

		$res['nova_senha']=md5($res['senha']);
		$sql= "UPDATE ".TABLE_PREFIX."_administrador SET
		  adm_senha=?";
		$sql.=" WHERE adm_id=?";

		if ($qry=$conn->prepare($sql)) {

			$qry->bind_param('si', $res['nova_senha'],$res['item']); 
			$qry->execute();


			if ($qry==false) echo divAlert('Ocorreu algum erro!');
			else {
				# define nome e email para enviar ao include de email
				$res['email'] = $_SESSION['user']['email'];
				$res['nome']  = $_SESSION['user']['nome'];
				$senha		  = $res['senha'];

				echo divAlert('Sua senha foi alterada!', 'success');
				include_once 'inc.email.php';
			} 


			$qry->close();
		}




	} else
		echo divAlert('Sua atual não confere! Tente novamente.');


}

include_once 'alterasenha.form.php';
