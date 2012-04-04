<?php
/*
 * LOGIN
 */
session_start();
$rp			= 'admin/';
$_GET['p']  = 'cadastro';

include_once $rp.'_inc/global.php';
include_once $rp.'_inc/db.php';
include_once $rp.'_inc/global_function.php';
include_once $rp.'cadastro/mod.var.php';

	/*
	 *antes de tudo verifica se está logado
	 */
	if (isset($_SESSION[TP]['login']) && !empty($_SESSION[TP]['login'])) {

		$resp = null;
		foreach ($_SESSION[TP] as $key=>$val)
			$resp .= $key.'='.$val."&";

		echo substr($resp, 0, -1);
		die();

	} else
		unset($_SESSION[TP]);


	/*
	 *verifica se todos os parametros estao ok
	 */
	if (!isset($_POST['cpf']) || !isset($_POST['nascimento']))
		die('Atenção: Faltam parâmetros!');


	/*
	 *começa as validações
	 */

	//$res['nascimento'] = $_POST['nasc_dia'].'/'.$_POST['nasc_mes'].'/'.$_POST['nasc_ano'];
	$res['cpf'] = trim($_POST['cpf']);
	$res['nascimento'] = trim($_POST['nascimento']);

	list($dia, $mes, $ano) = explode('/', $res['nascimento']);
	$res['nascimento'] = $ano.'-'.$mes.'-'.$dia;


	if (empty($res['cpf']) || empty($res['nascimento']))
		echo 'CPF ou data de nascimento em branco!';

	elseif(!validaCPF($res['cpf']))
		echo 'CPF inválido!';

	elseif(!validaNascimento($ano, $mes, $dia))
		echo 'Data de nascimento inválida!';

	else {

		$sql = "SELECT
					{$var['pre']}_id,
					{$var['pre']}_nome,
					{$var['pre']}_nome_limpo,
					{$var['pre']}_email,
					{$var['pre']}_cpf,
					{$var['pre']}_rg,
					{$var['pre']}_endereco,
					{$var['pre']}_numero,
					{$var['pre']}_complemento,
					{$var['pre']}_bairro,
					{$var['pre']}_cep,
					{$var['pre']}_cidade,
					{$var['pre']}_uf,
					{$var['pre']}_sexo,
					{$var['pre']}_telefone,
					{$var['pre']}_source,
					{$var['pre']}_status,
					DATE_FORMAT({$var['pre']}_nascimento, '%d/%m/%Y') nascimento,
					DATE_FORMAT({$var['pre']}_timestamp, '%d/%m/%Y') cadastro,
					DATE_FORMAT({$var['pre']}_ultimo_login, '%d/%m/%Y') ultimo_login
				FROM ".TABLE_PREFIX."_${var['path']}";
		$sql.=" WHERE ${var['pre']}_cpf=? AND ${var['pre']}_nascimento=?";

		if (!$qry=$conn->prepare($sql))
			die('Houve um erro na tentativa de realizar a consulta de cadastro! Contate o desenvolvedor.');
			//die($conn->error);

		else {

			$qry->bind_param('ss', $res['cpf'], $res['nascimento']);
			$qry->execute();
			$qry->bind_result($id, $nome, $nome_limpo, $email, $cpf, $rg, $endereco, $numero, $complemento, $bairro, $cep, $cidade, $uf, $sexo, $telefone, $source, $status, $nascimento, $cadastro, $ultimo_login);
			$qry->store_result();
			$num = $qry->num_rows;
			$qry->fetch();
			$qry->close();

			if ($num==0)
				echo 'CPF ou Data de Nascimento inválido ou usuário não cadastrado!';

			else {


				/*
				 *atualiza data do ultimo login
				 */
				$sql_updlogin = "UPDATE ".TABLE_PREFIX."_${var['path']} SET ${var['pre']}_ultimo_login=NOW()";
				if (!$qry_updlogin=$conn->prepare($sql_updlogin))
				   echo 'Houve um erro ao tentar atualizar ultimo login. Contate o desenvolvedor';
				   //echo $conn->error;

				else {

					$qry_updlogin->execute();
					$qry_updlogin->close();


					$_SESSION[TP] = array(
						'id'=> $id,
						'nome'=> $nome,
						'email'=> $email,
						'cidade'=> $cidade,
						'uf'=> $uf,
						'cep'=> $cep,
						'nascimento'=> $nascimento,
						'cpf'=> $cpf,
						'ultimo_login'=> $ultimo_login,
						'cadastro'=> $cadastro
					);

					var_dump($_SESSION[TP]);
					return true;

				}

			}


		}

	}
