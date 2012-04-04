<?php

  foreach($_POST as $chave=>$valor) {
   $res[$chave] = $valor;
  }


# include de mensagens do arquivo atual
 include_once 'inc.exec.msg.php';


 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
 $sql_valida = "SELECT adm_email retorno FROM ".TABLE_PREFIX."_administrador WHERE adm_email=? AND adm_tipo='Professor'";
 $qry_valida = $conn->prepare($sql_valida);
 $qry_valida->bind_param('s', $res['email']); 
 $qry_valida->execute();
 $qry_valida->store_result();

  #se existe um titulo/nome/email assim nao passa
  if ($qry_valida->num_rows<>0 && $act=='insert') {
   echo $msgDuplicado;
   $qry_valida->close();


  #se nao existe faz a inserção
  } else {

     #autoinsert
     include_once $rp.'inc.autoinsert.php';

	 $qry=false;
     $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

  		  ${var['pre']}_registro=?,
  		  ${var['pre']}_cpf=?,
  		  ${var['pre']}_rg=?,
		  ${var['pre']}_telefone=?,
		  ${var['pre']}_celular=?,
		  ${var['pre']}_titulos=?
	";
     $sql.=" WHERE ${var['pre']}_id=?";
	 if (!$qry=$conn->prepare($sql))
		 echo $conn->error;

	 else {

		 $qry->bind_param('ssssssi',
			 $res['registro'],
			 $res['cpf'],
			 $res['rg'],
			 $res['telefone'],
			 $res['celular'],
			 $res['titulos'],
			 $res['item']); 
		 $qry->execute();

	 }


   if ($qry==false) echo $msgExiste;
    else {
     
     $qry->close();

      /*
      *se for inserçao é cria uma senha e envia por email
      */
	 $res['tipo'] = 'Professor';
     if ($act=='insert') {
      $senha	     = gera_senha(3);
      $form['senha'] = md5($senha);

		/*
		 *CRIA CONTA NA TABELA DE ADMINISTRAÇÃO
		 */
		$sql_senha = "INSERT INTO ".TABLE_PREFIX."_administrador (adm_nome, adm_email, adm_senha, adm_tipo, adm_n)";
		$sql_senha.= " VALUES (?, ?, ?, ?, ?)";
		$qry_senha=$conn->prepare($sql_senha);
		$qry_senha->bind_param('sssss', $res['nome'], $res['email'], $form['senha'], $res['tipo'], $n); 
		$qry_senha->execute();

		/*
		 *RESGATA ID DA NOVA CONTA NA TABELA DE ADMINISTRAÇÃO
		 */
		$sql_id = "SELECT adm_id item FROM ".TABLE_PREFIX."_administrador WHERE adm_n='${n}'";
		$qry_id = $conn->query($sql_id);
		$res_id = $qry_id->fetch_array();
		$adm_id = $res_id['item'];
		$qry_id->close();

		/*
		 *ATUALIZA TABELA DE CLIENTES COM ESSE NOVO ID
		 */
		$sql_upd = "UPDATE ".TABLE_PREFIX."_${var['path']} SET ${var['pre']}_adm_id=?";
		$sql_upd.=" WHERE ${var['pre']}_id=?";
		$qry_upd=$conn->prepare($sql_upd);
		$qry_upd->bind_param('ii', $adm_id, $res['item']); 
		$qry_upd->execute();

		/*
		 *ADICIONA PERMISSAO PARA O MODULO DE ORÇAMENTO
		 */
		$mod_id = 21;
		$sql_orc = "INSERT INTO ".TABLE_PREFIX."_r_adm_mod (ram_adm_id, ram_mod_id)";
		$sql_orc.= " VALUES (?, ?)";
		$qry_orc=$conn->prepare($sql_orc);
		$qry_orc->bind_param('ii', $adm_id, $mod_id); 
		$qry_orc->execute();


	   //include_once 'inc.email.php';

     } else {

	   $adm_id = $res['adm_id'];
	   /*
		*ALTERA NOME, EMAIL E SENHA
		*/
       $sql_adm= "UPDATE ".TABLE_PREFIX."_administrador SET adm_email=?, adm_nome=?";
       $sql_adm.=" WHERE adm_id=? AND adm_tipo=?";
       $qry_adm=$conn->prepare($sql_adm);
       $qry_adm->bind_param('ssis', $res['email'], $res['nome'], $adm_id, $res['tipo']); 
       $qry_adm->execute();

	   /*
		*ALTERA SENHA, SE SENHA NAO VIER VAZIA
		*/
	   if (isset($res['senha']) && !empty($res['senha'])) {
		   $senha	      = $res['senha'];
		   $form['senha'] = md5($senha);

		   $sql_adm= "UPDATE ".TABLE_PREFIX."_administrador SET adm_senha=? ";
		   $sql_adm.=" WHERE adm_id=? AND adm_tipo=?";
		   $qry_adm=$conn->prepare($sql_adm);
		   $qry_adm->bind_param('sis', $form['senha'], $adm_id, $res['tipo']); 
		   $qry_adm->execute();

		   include_once 'inc.email.php';

	   }


		/*
		 *VERIFICA SE JÁ EXISTE PERMISSÃO AO MODULO DE ORÇAMENTO
		 */
		$mod_id = 21;
		$sql_pid = "SELECT COUNT(*) num FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=${adm_id} AND ram_mod_id={$mod_id}";
		$qry_pid = $conn->query($sql_pid);
		$res_pid = $qry_pid->fetch_array();
		$pnum = $res_pid['num'];
		$qry_pid->close();

		/*
		 *ADICIONA PERMISSAO PARA O MODULO DE ORÇAMENTO
		 */
		if ($pnum==0) {
			$sql_orc = "INSERT INTO ".TABLE_PREFIX."_r_adm_mod (ram_adm_id, ram_mod_id)";
			$sql_orc.= " VALUES (?, ?)";
			$qry_orc=$conn->prepare($sql_orc);
			$qry_orc->bind_param('ii', $adm_id, $mod_id); 
			$qry_orc->execute();
		}

	 }   

	 include_once 'mod.exec.atividade.php';
	 include_once 'mod.exec.galeria.php';
     echo $msgSucesso;
	 //include_once 'inc.email.php';

    }

 }

// mostra listagem
include_once 'list.php';
