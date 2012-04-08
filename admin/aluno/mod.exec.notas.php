<?php
	/*
	 *cabeçalho
	 */
	$rp = '../';
	include_once $rp.'_inc/global.php';
	include_once $rp.'_inc/db.php';
	include_once $rp.'_inc/global_function.php';
	include_once $rp.'inc.auth.php';



	foreach($_POST as $chave=>$valor)
		$res[$chave] = $valor;


	$recuperacao_numperiodo = $res['recuperacao_periodo_num'];
	$max_numperiodo = $res['recuperacao_periodo_num']-1;
	$final_numperiodo = $res['final_periodo_num'];


	/*
	 *remove notas dos alunos
	 */
	foreach ($res['val'] as $adm_id=>$val) {
		$sql_dmod = "DELETE FROM ".TABLE_PREFIX."_r_alu_notas WHERE ran_adm_id=? AND ran_turma_id=?";
		$qry_dmod = $conn->prepare($sql_dmod);
		$qry_dmod->bind_param('ii', $adm_id, $res['turma_id']); 
		$qry_dmod->execute();
		$qry_dmod->close();
	}


	/*
	 *soma todas as medias
	 */
	$_falta = $_media = $_mediaCount = array();
	foreach ($res['val'] as $adm_id=>$notas) {
		foreach ($notas as $periodo_num=>$arr) {

			foreach ($arr as $disciplina_id=>$row) {
				if (isset($row['media']) && strlen($row['media'])>0) {


					if (!isset($_media[$adm_id][$disciplina_id])) {
					   $_media[$adm_id][$disciplina_id] = array();
					   $_falta[$adm_id][$disciplina_id] = 0;
					}

					array_push($_media[$adm_id][$disciplina_id], $row['media']);
					$_falta[$adm_id][$disciplina_id] += $row['falta'];

				}
			}

		}
	}


	/*
	 *calcula média anual
	 */
	foreach ($res['val'] as $adm_id=>$notas) {
		foreach ($notas as $periodo_num=>$arr) {

			//if ($periodo_num>$max_numperiodo)
				//continue;

			foreach ($arr as $disciplina_id=>$row) {

				if (isset($row['media']) && strlen($row['media'])>0) {
					$_mediaVal = array_sum($_media[$adm_id][$disciplina_id]);
					$_mediaNum = count($_media[$adm_id][$disciplina_id]);

					if ($_mediaNum>0) {
						$res['val'][$adm_id][$final_numperiodo][$disciplina_id]['media'] = number_format(($_mediaVal/$_mediaNum), 1);
						$res['val'][$adm_id][$final_numperiodo][$disciplina_id]['falta'] = $_falta[$adm_id][$disciplina_id];
					}

				}

			}

		}
	}


	/*
	 *calcula média anual com recuperação
	 */
	foreach ($res['val'] as $adm_id=>$notas) {
		foreach ($notas as $periodo_num=>$arr) {
			if ($periodo_num==$recuperacao_numperiodo) {
				foreach ($arr as $disciplina_id=>$row) {

					if (isset($row['media']) && strlen($row['media'])>0) {

						$_mediaVal = array_sum($_media[$adm_id][$disciplina_id]);
						$_mediaNum = count($_media[$adm_id][$disciplina_id]);
						$res['val'][$adm_id][$final_numperiodo][$disciplina_id]['media'] = number_format(((($_mediaVal/$_mediaNum)+$row['media'])/2), 1);
						$res['val'][$adm_id][$final_numperiodo][$disciplina_id]['falta'] = $_falta[$adm_id][$disciplina_id]+$row['falta'];

					}

				}
			}
		}
	}



	/*
	 *registra notas atualizadas na tabela
	 */
	$sql = "INSERT INTO ".TABLE_PREFIX."_r_alu_notas 
				(
					ran_adm_id,
					ran_disciplina_id,
					ran_professor_id,
					ran_turma_id,
					ran_periodo_tipo,
					ran_periodo_num,
					ran_media,
					ran_falta,
					ran_final,
					ran_ip,
					ran_status
				) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
	if(!$qry = $conn->prepare($sql))
		echo divAlert($conn->error);

	else {

		$res['professor_id'] = intval($_SESSION['user']['id']);
		$res['periodo_tipo'] = 'Bimestre';
		$res['ip']			 = $_SERVER['REMOTE_ADDR'];
		$res['status']		 = 1;

		foreach ($res['val'] as $adm_id=>$notas) {
			foreach ($notas as $periodo_num=>$arr) {
				foreach ($arr as $disciplina_id=>$row) {

					$res['final'] = $periodo_num==$final_numperiodo ? 1 : 0;
					if (strlen($row['media'])>0) {

						$row['media'] = sprintf("%0.1f", $row['media']);
						$qry->bind_param("iiiisidiisi", 
							$adm_id,
							$disciplina_id,
							$res['professor_id'],
							$res['turma_id'],
							$res['periodo_tipo'],
							$periodo_num,
							$row['media'],
							$row['falta'],
							$res['final'],
							$res['ip'],
							$res['status']
						);

						$ok = $qry->execute();

					}

				}
			}
		}

		if ($ok)
			echo divAlert("As notas de ".count($res['val'])." alunos foram atualizadas!", 'success');
		else
			echo divAlert("Houve um erro ao tentar atualizar as notas, entre em contato com o <a href='mailto:".ADM_EMAIL."'>desenvolvedor</a>!", 'success');


		$qry->close();

	}
