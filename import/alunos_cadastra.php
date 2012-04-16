<?php

$res['email']= removeAcentos(trim(strtolower($res['email'])));
$res['email_responsavel']= removeAcentos(trim(strtolower($res['email_responsavel'])));
$res['email_responsavel2']= removeAcentos(trim(strtolower($res['email_responsavel2'])));

if (empty($res['nome']) || empty($res['email']) || empty($res['turma_nome']))
	echo $res['nome'].' <b>NÃO</b> possui email ('.$res['email'].') ou turma ('.$res['turma_nome'].') informadas!<br/>';

elseif (empty($res['email']) || !validaEmail($res['email']))
	echo $res['nome'].' <b>NÃO</b> possui um email ('.$res['email'].') válido!<br/>';

else {

	if (isset($res['email_responsavel']) && !empty($res['email_responsavel']) && !validaEmail($res['email_responsavel'])) {
		echo $res['nome'].' <b>NÃO</b> possui um email de responsavel ('.$res['email_responsavel'].') válido! Esse email será omitido<br/>';
		$res['email_responsavel'] = null;
	}

	if (isset($res['email_responsavel2']) && !empty($res['email_responsavel2']) && !validaEmail($res['email_responsavel2'])) {
		echo $res['nome'].' <b>NÃO</b> possui um email de responsavel 2 ('.$res['email_responsavel2'].') válido! Esse email será omitido<br/>';
		$res['email_responsavel2'] = null;
	}



	$res['turma_nome'] = mb_strtolower($res['turma_nome'], 'utf8');
	$sql_mod = "SELECT cat_id FROM ".TABLE_PREFIX."_categoria WHERE LOWER(cat_titulo)='{$res['turma_nome']}'";
	$qry_mod = $conn->query($sql_mod);
	$rs = $qry_mod->fetch_array();
	$qry_mod->close();
	$turma_id = $rs['cat_id'];

	if (empty($turma_id))
		echo $res['nome'].' <b>NÃO</b> possui uma turma válida ('.$res['turma_nome'].') com um nome exatamente igual ao que está cadastrado no sistema!<br/>'.$sql_mod.'<br/><br/>';

	else {


	 ## verifica se existe um titulo/nome/email com o mesmo nome do que esta sendo inserido
	 $sql_valida = "SELECT adm_email retorno FROM ".TABLE_PREFIX."_administrador WHERE adm_email=?";
	 $qry_valida = $conn->prepare($sql_valida);
	 $qry_valida->bind_param('s', $res['email']); 
	 $qry_valida->execute();
	 $qry_valida->store_result();
	 $num = $qry_valida->num_rows;
	 $qry_valida->close();


	  if ($num==0) {

		 #autoinsert
		 $n = gera_senha(65).date('Ymdhi').substr(md5($res['nome']), 0, 10);
		 include $rp.'inc.autoinsert.php';

		 $res['tipo'] = 'Aluno';
		 $qry=false;
		 $sql= "UPDATE ".TABLE_PREFIX."_${var['path']} SET

			  ${var['pre']}_registro=?,
			  ${var['pre']}_cpf=?,
			  ${var['pre']}_rg=?,
			  ${var['pre']}_telefone=?,
			  ${var['pre']}_celular=?,
			  ${var['pre']}_nome_financeiro=?,
			  ${var['pre']}_email_financeiro=?,
			  ${var['pre']}_telefone_financeiro=?,
			  ${var['pre']}_celular_financeiro=?,
			  ${var['pre']}_nome_responsavel=?,
			  ${var['pre']}_email_responsavel=?,
			  ${var['pre']}_nome_responsavel2=?,
			  ${var['pre']}_email_responsavel2=?
		";
		 $sql.=" WHERE ${var['pre']}_id=?";
		 if (!$qry=$conn->prepare($sql))
			 echo $conn->error;

		 else {

			 $qry->bind_param('sssssssssssssi',
				 $res['registro'],
				 $res['cpf'],
				 $res['rg'],
				 $res['telefone'],
				 $res['celular'],
				 $res['nome_financeiro'],
				 $res['email_financeiro'],
				 $res['telefone_financeiro'],
				 $res['celular_financeiro'],
				 $res['nome_responsavel'],
				 $res['email_responsavel'],
				 $res['nome_responsavel2'],
				 $res['email_responsavel2'],
				 $res['item']); 
			 $qry->execute();

		 }


	   if ($qry==false) echo $conn->error;
		else {
		 
		 $qry->close();

		  /*
		  *se for inserçao é cria uma senha e envia por email
		  */
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
			$adm_id = $res['adm_id'] = $res_id['item'];
			$qry_id->close();

			/*
			 *ATUALIZA TABELA DE CLIENTES COM ESSE NOVO ID
			 */
			$sql_upd = "UPDATE ".TABLE_PREFIX."_${var['path']} SET ${var['pre']}_adm_id=?";
			$sql_upd.=" WHERE ${var['pre']}_id=?";
			$qry_upd=$conn->prepare($sql_upd);
			$qry_upd->bind_param('ii', $adm_id, $res['item']); 
			$qry_upd->execute();

			include 'alunos_cadastra.turmas.php';
			include 'alunos.inc.email.php';

			echo 'Aluno '.$res['nome'].' cadastrado com êxito<br/>';

		}

	 }

	}
}
