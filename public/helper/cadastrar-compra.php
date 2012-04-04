<?php
/*
 * NEW USER
 */
$rp			= 'admin/';

session_start();
include_once $rp.'_inc/global.php';
include_once $rp.'_inc/db.php';
include_once $rp.'_inc/global_function.php';
include_once 'lang.php';
include_once 'globalVars.php';
include_once 'geraCupons.php';

	foreach ($_POST as $key=>$val)
		$res[$key] = mb_strtoupper(trim($val), 'utf8');


	/*
	 *antes de tudo verifica se está logado
	 */
	if (!isset($_SESSION[TP]['cpf']) || empty($_SESSION[TP]['cpf']))
		die('Você não está logado!');


	//agrupa data de nascimento
	$res['data'] = $res['compra_dia'].'/'.$res['compra_mes'].'/'.$res['compra_ano'];
    $res['valor'] = Currency2Decimal($res['valor'], 1);

	/*
	 *validação
	 */
	$err=null;

	//campos obrigatorios
	$fieldsRequired = array(
		'coo'=>'string',
		'ccf'=>'string',
		'cnpj'=>'cnpj',
		'cidade'=>'string',
		'estado'=>'string',
		'data'=>'data',
		'valor'=>'string',
	);

	foreach ($res as $label=>$value) {
		if (in_array($label, $fieldsRequired)) {

			$ano = $mes = $dia = null;
			$data = $label=='data' || $label=='nascimento' ? $value : null;
			if (!empty($data)) {
				list($dia, $mes, $ano) = explode('/', $data);
				$res[$label] = $ano.'-'.$mes.'-'.str_pad($dia, 2, 0, STR_PAD_LEFT);;
			}

			$reqType = $fieldsRequired[$label];
			if ($reqType=='string' && empty($value))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='int' && !apenasNumeros($value))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='cpf' && !validaCPF($value))
				$err .= $_lng['empty_'.$label];

			elseif ($reqType=='cnpj' && !validaCNPJ($value))
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

		$sql = "SELECT NULL FROM ".TABLE_PREFIX."_compra ";
		$sql.=" WHERE cpr_coo=?";

		if (!$qry=$conn->prepare($sql))
			echo showModal($_lng['error_consulta']);

		else {
			$qry->bind_param('s', $res['coo']);
			$qry->execute();
			$qry->store_result();
			$num = $qry->num_rows;
			$qry->fetch();
			$qry->close();
		}

		if ($num>0)
			echo showModal($_lng['coo_existente']);

		else {

			/*
			 *SQL cadastro da compra
			 */
			$sql= "INSERT INTO ".TABLE_PREFIX."_compra
				(
					cpr_cad_id,
					cpr_produto_bonus,
					cpr_coo,
					cpr_ccf,
					cpr_cnpj,
					cpr_cidade,
					cpr_estado,
					cpr_valor,
					cpr_datahora_compra,
					cpr_ip
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?) ";

			if (!$qry=$conn->prepare($sql))
				echo showModal($_lng['error_cadastro']);
				//echo $conn->error;

			else {

				$res['datahora_compra'] = $res['data'].' '.$res['compra_hora'];
				$res['produto_bonus'] = null;
				$res['ip'] = filter_var($_SERVER['REMOTE_ADDR'], FILTER_VALIDATE_IP);
				$qry->bind_param('issssssdss',
					$uid,
					$res['produto_bonus'],
					$res['coo'],
					$res['ccf'],
					$res['cnpj'],
					$res['cidade'],
					$res['estado'],
					$res['valor'],
					$res['datahora_compra'],
					$res['ip']
				); 
				$qry->execute();
				$qry->close();


				//resgata id da compra cadastrado
				$sqlcheck = "SELECT cpr_id FROM ".TABLE_PREFIX."_compra";
				$sqlcheck.=	" WHERE cpr_coo=? AND cpr_cad_id=?";

				if (!$qrycheck=$conn->prepare($sqlcheck))
					echo showModal($_lng['error_pesquisa']);
					//echo $conn->error;

				else {

					$qrycheck->bind_param('si', $res['coo'], $uid); 
					$qrycheck->execute();
					$qrycheck->bind_result($id);
					$qrycheck->fetch();
					$qrycheck->close();

					$cpr_id = $id;


					/*
					 *gera cupons para o usuário
					 */
					/*
					$numCupons= strstr($res['valor'], '.', true);

					//de acordo com o valor entrado, esse é o número de cupons a ser
					//gerado para essa compra!
					for ($i=0; $i<$numCupons; $i++)
						gerarCupom($cpr_id);
					*/

					/*
					 *teste gearman
					 */
					$numCupons= strstr($res['valor'], '.', true);
					$client = new GearmanClient();
					$client->addServer();
					$value = $client->do('geraCupons', $cpr_id, $numCupons);

					switch ($client->returnCode())
					{
							case GEARMAN_SUCCESS:
								echo "\nSUCCESS ".$value;
						break;
							case GEARMAN_WORK_FAIL:
								echo "\nFAILED";
						break;
							case GEARMAN_WORK_STATUS:
								list($numerator, $denominator) = $client->doStatus();
								echo "Status: $numerator/$denominator\n";
						break;
							default:
								echo "\nERR: " . $client->error() . "\n";
						break;
					}






					//header('location: ./obrigado');
					echo "\n\t\twindow.location='./compra-realizada';";
				}
			}

		}

	}
