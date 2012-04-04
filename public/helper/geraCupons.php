<?php
/*
	session_start();

	$rp			= '../admin/';
	include_once $rp.'_inc/global.php';
	include_once $rp.'_inc/db.php';
 */


	/*
	 * GERA CÖDIGO RANDOMICO UNICO PARA SORTEIO
	 * cpr_id id da compra (hgg_compra)
	 * resposta bool
	 */
	function gerarCupom ($cpr_id, $numCupons, $_instantaneo=false)
	{
		global $conn, $uid;



		//variaveis globais
		$_table = $_instantaneo===false ? 'cupom' : 'cupom_instantaneo';
		$_pfx   = $_instantaneo===false ? 'cpm_' : 'cpi_';


		//filtra
		if (preg_match('/[^0-9]/', $uid) || preg_match('/[^0-9]/', $cpr_id) || (preg_match('/[^0-9]/', $numCupons) || $numCupons<1))
			exit('Parâmetros inválidos!');

		//variaveis 
		$compra_id	= intval($cpr_id);
		$numCupons	= intval($numCupons);



		for ($i=0; $i<$numCupons; $i++) {


			//gera o código e verifica se ele já existe antes de continuar
			do {

				$random = rand(1, 2);
				$random .= rand(1, 99999999);
				$cod = sprintf("%09d", (int)$random);

				$sql = "SELECT NULL FROM `".TABLE_PREFIX."_{$_table}` WHERE `{$_pfx}codigo`=$cod";
				$res = $conn->query($sql);

			} while ($res->num_rows>0);



			//insere o código na tabela
			$sqlins = "INSERT INTO `".TABLE_PREFIX."_{$_table}` (`{$_pfx}codigo`, `{$_pfx}cad_id`, `{$_pfx}cpr_id`) VALUES (?, ?, ?)";
			if (!$qryins = $conn->prepare($sqlins))
				return false;
				//echo $conn->error;

			else {

				$qryins->bind_param('sii', $cod, $uid, $compra_id);
				$qryins->execute();
				$qryins->close();

				echo 'Código '.$cod.' cadastrado!<br/>';
				//return true;

			}

		}

	}
