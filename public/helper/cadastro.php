<?php
/*
 * NEW USER
 */
$rp			= 'admin/';
$_GET['p']  = 'cadastro';

include_once $rp.'_inc/global.php';
include_once $rp.'_inc/db.php';
include_once $rp.'_inc/global_function.php';
include_once $rp.'cadastro/mod.var.php';
include_once 'lang.php';

	foreach ($_POST as $key=>$val)
		$res[$key] = mb_strtoupper(trim($val), 'utf8');

	//agrupa data de nascimento
	$res['nascimento'] = $res['nasc_dia'].'/'.$res['nasc_mes'].'/'.$res['nasc_ano'];

	/*
	 *validação
	 */
	$err=null;

	//campos obrigatorios
	$fieldsRequired = array(
		'source'=>'string',
		'nome'=>'string',
		'email'=>'email',
		'cpf'=>'cpf',
		'rg'=>'string',
		'nascimento'=>'nascimento',
		'endereco'=>'string',
		'numero'=>'string',
		'bairro'=>'string',
		'cidade'=>'string',
		'uf'=>'string',
		'cep'=>'string',
		'sexo'=>'string',
		'telefone'=>'string',
		'termo'=>'bool',
	);

	foreach ($res as $label=>$value) {
		if (in_array($label, $fieldsRequired)) {

			$ano = $mes = $dia = null;
			$data = $label=='data' || $label=='nascimento' ? $value : null;
			if (!empty($data)) {
				list($dia, $mes, $ano) = explode('/', $data);
				$res[$label] = $ano.'-'.$mes.'-'.$dia;
			}

			$reqType = $fieldsRequired[$label];
			if ($reqType=='string' && empty($value))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='int' && !apenasNumeros($value))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='cpf' && !validaCPF($value))
				$err .= $_lng['empty_'.$label];

			if ($reqType=='bool' && !$value)
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='email' && !validaEmail($value))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='date' && !validaData($ano, $mes, $dia))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='nascimento' && !validaNascimento($ano, $mes, $dia))
				$err .= $_lng['empty_'.$label];

		}
	}


	if (!is_null($err))
		echo showModal($err);

	else {

		$sql = "SELECT NULL FROM ".TABLE_PREFIX."_${var['path']} ";
		$sql.=" WHERE ${var['pre']}_cpf=?";

		if (!$qry=$conn->prepare($sql))
			echo showModal($_lng['error_consulta']);

		else {
			$qry->bind_param('s', $res['cpf']);
			$qry->execute();
			$qry->store_result();
			$num = $qry->num_rows;
			$qry->fetch();
			$qry->close();
		}

		if ($num>0)
			echo showModal($_lng['cpf_existente']);

		else {

			/*
			 *SQL cadastro
			 */
			$sql= "INSERT INTO ".TABLE_PREFIX."_${var['path']}
				(
					${var['pre']}_nome,
					${var['pre']}_nome_limpo,
					${var['pre']}_email,
					${var['pre']}_cpf,
					${var['pre']}_rg,
					${var['pre']}_nascimento,
					${var['pre']}_endereco,
					${var['pre']}_numero,
					${var['pre']}_complemento,
					${var['pre']}_bairro,
					${var['pre']}_cep,
					${var['pre']}_cidade,
					${var['pre']}_uf,
					${var['pre']}_sexo,
					${var['pre']}_telefone,
					${var['pre']}_source,
					${var['pre']}_ip
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

			if (!$qry=$conn->prepare($sql))
				echo showModal($_lng['error_cadastro']);
				//echo $conn->error;

			else {

				$res['email'] = mb_strtolower($res['email'], 'utf8');
				$res['nome_limpo'] = removeAcentos($res['nome']);
				$res['ip'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
				$qry->bind_param('sssssssssssssssss',
					$res['nome'],
					$res['nome_limpo'],
					$res['email'],
					$res['cpf'],
					$res['rg'],
					$res['nascimento'],
					$res['endereco'],
					$res['numero'],
					$res['complemento'],
					$res['bairro'],
					$res['cep'],
					$res['cidade'],
					$res['uf'],
					$res['sexo'],
					$res['telefone'],
					$res['source'],
					$res['ip']
				); 
				$qry->execute();
				$qry->close();


				//resgata id do user cadastrado
				$sqlcheck = "SELECT ${var['pre']}_id FROM ".TABLE_PREFIX."_${var['path']}";
				$sqlcheck.=	" WHERE ${var['pre']}_cpf=?";

				if (!$qrycheck=$conn->prepare($sqlcheck))
					echo showModal($_lng['error_pesquisa']);
					//echo $conn->error;

				else {

					$qrycheck->bind_param('s', $res['cpf']); 
					$qrycheck->execute();
					$qrycheck->bind_result($id);
					$qrycheck->fetch();
					$qrycheck->close();

					$res['item'] = $id;

					//header('location: ./obrigado');
					echo "\n\t\twindow.location='./obrigado';";
				}
			}

		}

	}
