<?php

	$idturma = intval($_POST['turma_id']);
	if (empty($idturma))
		die('<div class="alert alert-error">ID da turma inválido!</div>');

	$rp = '../';
	include_once $rp.'_inc/global.php';
	include_once $rp.'_inc/db.php';
	include_once $rp.'_inc/global_function.php';
	include_once $rp.'inc.auth.php';


	/*
	 *query da turma
	 */
	$sqla = "SELECT 
				alu_adm_id,
				alu_id,
				(SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) nome,
				(SELECT adm_email FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) email,
				alu_registro

			FROM ".TABLE_PREFIX."_aluno
				INNER JOIN ".TABLE_PREFIX."_r_alu_turmas
					ON rat_adm_id=alu_adm_id
			WHERE alu_status=1 AND rat_cat_id=?
			ORDER BY nome, alu_registro";

	$alunos = array();
	$lstAdmId = array();
	$total_alunos = 0;
	if(!$qrya = $conn->prepare($sqla))
		echo divAlert($conn->error, 'error');

	else {

		$qrya->bind_param('i', $idturma);
		$qrya->execute();
		$qrya->store_result();
		$total_alunos = $qrya->num_rows;
		$qrya->bind_result($adm_id, $id, $nome, $email, $registro);

		while ($qrya->fetch()) {
			$lstAdmId[$adm_id] = $adm_id;
			$alunos[$adm_id]['adm_id'] = $adm_id;
			$alunos[$adm_id]['nome'] = $nome;
			$alunos[$adm_id]['email'] = $email;
			$alunos[$adm_id]['registro'] = $registro;
		}

		$qrya->close();


	}

	if ($total_alunos==0)
		echo divAlert('Ainda não existem alunos para essa turma!', 'warning');


	else {


	?>
	<p class='header'>Selecione um ou mais alunos:</p>
	<form method='post' action='index.php?p=aluno&lancar-notas' id='formnotas' class='form-horizontal cmxform'>
	<?php
		echo "<input type='hidden' name='turma_id' value='{$idturma}'>";
	?>
	<select name='alu_id[]' id='alu_id' multiple size=10>
		<option value='all'>Lançar notas para Todos</option>
		<option value=''>-----------------------</option>
		<?php
			foreach ($alunos as $id=>$aluno)
				echo "\n\t\t<option value='{$id}'><small class='label'>{$aluno['registro']}</small> {$aluno['nome']}</option>";
		?>
	</select>

	<div class='form-actions' align=center>
		<input type='submit' value='Listar Alunos' class='btn btn-primary'>
	</div>
	<div class='msgReturn'></div>

	</form>
<?php
	}
?>
