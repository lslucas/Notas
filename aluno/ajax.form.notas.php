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
	 *lista matérias do professor
	 */
	$lstProfDisciplinas = null;
	if (isset($_SESSION['user']['tipo']) && $_SESSION['user']['tipo']=='Professor') {
		$adm_id = $_SESSION['user']['id'];

		$sqlm = "SELECT rpd_cat_id FROM ".TABLE_PREFIX."_r_prof_disciplinas
					WHERE rpd_adm_id=? AND rpd_cat_id IS NOT NULL";

		$profDisciplinas = array();
		if($qrym = $conn->prepare($sqlm)) {
			$qrym->bind_param('i', $adm_id);
			$qrym->execute();
			$qrym->bind_result($cat_id);

			while ($qrym->fetch())
				$profDisciplinas[$cat_id] = $cat_id;

			$qrym->close();
			$lstProfDisciplinas = join(', ', $profDisciplinas);
		}
	}


	/*
	 *query da turma
	 */
	$sqla = "SELECT 
				alu_adm_id,
				alu_id,
				(SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) nome,
				(SELECT adm_email FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) email,
				alu_registro,
				alu_telefone,
				alu_celular

			FROM ".TABLE_PREFIX."_aluno
				INNER JOIN ".TABLE_PREFIX."_r_alu_turmas
					ON rat_adm_id=alu_adm_id
			WHERE alu_status=1 AND rat_cat_id=?
			ORDER BY nome, alu_registro";
//echo $sqla;
//echo '<br/>'.$idturma;
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
		$qrya->bind_result($adm_id, $id, $nome, $email, $registro, $telefone, $celular);

		while ($qrya->fetch()) {
			$lstAdmId[$adm_id] = $adm_id;
			$alunos[$adm_id]['adm_id'] = $adm_id;
			$alunos[$adm_id]['nome'] = $nome;
			$alunos[$adm_id]['email'] = $email;
			$alunos[$adm_id]['registro'] = $registro;
			$alunos[$adm_id]['telefone'] = $telefone;
			$alunos[$adm_id]['celular'] = $celular;
		}

		$qrya->close();


	}

	if ($total_alunos==0)
		echo divAlert('Ainda não existem alunos para essa turma!', 'warning');


	else {

		//array com disciplinas
		$arrayDisciplinas = getListDisciplinas();
		$listDisciplinas = array();
		foreach ($arrayDisciplinas as $disc_id=>$disc_nome) {
			if (isset($profDisciplinas) && !in_array($disc_id, $profDisciplinas))
				continue;

			$listDisciplinas[$disc_id] = $disc_nome;
		}

		//transforma em lista
		$lstAdmId = join(', ', $lstAdmId);
		$lstAdmId = empty($lstAdmId) ? 0 : $lstAdmId;

		if (!empty($lstProfDisciplinas))
			$qryProfDisciplinas = "AND ran_disciplina_id IN ({$lstProfDisciplinas})";
		else
			$qryProfDisciplinas = null;

		/*
		 *retorna valores das notas já cadastradas
		 */
		$sqlr = "SELECT
						ran_adm_id,
						ran_disciplina_id,
						ran_professor_id,
						ran_turma_id,
						ran_periodo_tipo,
						ran_periodo_num,
						ran_media,
						ran_falta
					FROM ".TABLE_PREFIX."_r_alu_notas 
					WHERE ran_adm_id IN ({$lstAdmId})
					{$qryProfDisciplinas}
					AND ran_turma_id=?";

		$val = array();
		if(!$qryr = $conn->prepare($sqlr))
			echo divAlert($conn->error);

		else {

			$qryr->bind_param('i', $idturma);
			$qryr->bind_result(
				$adm_id,
				$disciplina_id,
				$professor_id,
				$turma_id,
				$periodo_tipo,
				$periodo_num,
				$media,
				$falta
			);
			$qryr->execute();

			while($qryr->fetch()) {
				$val[$adm_id][$periodo_num][$disciplina_id]['media'] = $media;
				$val[$adm_id][$periodo_num][$disciplina_id]['falta'] = $falta;
			}

			$qryr->close();

		}

	?>
	<p class='header'>Coloque apenas decimais, de 0 a 10, exemplos: 5, 10, 8.5</p>

	<form method='post' action='aluno/mod.exec.notas.php' id='notas' class='form-horizontal cmxform'>
	<?php
		echo "<input type='hidden' name='turma_id' value='{$idturma}'>";
		echo "<input type='hidden' name='recuperacao_periodo_num' value='5'>";
		echo "<input type='hidden' name='final_periodo_num' value='6'>";
	?>
	<div class='msgReturn'></div>
	<table width='100%' class="table table-condensed table-striped">
	   <thead> 
		  <tr>
			<th colspan=2 rowspan=2>Aluno</th>
			<th width="150px" colspan=2 rowspan=2>Disciplinas</th>
			<th width="60px" colspan=2><center>1º Bimestre</center></th>
			<th width="60px" colspan=2><center>2º Bimestre</center></th>
			<th width="60px" colspan=2><center>3º Bimestre</center></th>
			<th width="60px" colspan=2><center>4º Bimestre</center></th>
			<th width="30px" align=center class='tip' title='Recuperação'>Recup.</th>
			<th width="60px" colspan=2><center>Final</center></th>
		  </tr>
		  <tr>
			<th width="30px">Média</th>
			<th width="30px">Faltas</th>
			<th width="30px">Média</th>
			<th width="30px">Faltas</th>
			<th width="30px">Média</th>
			<th width="30px">Faltas</th>
			<th width="30px">Média</th>
			<th width="30px">Faltas</th>
			<th width="30px" align=center>Média</th>
			<!--<th width="30px">Faltas</th>-->
			<th width="30px">Média</th>
			<th width="30px">Faltas</th>
		  </tr>
	   </thead>  
	   <tbody>
		<?php
			foreach ($alunos as $id=>$aluno) {

				$w=0;
				foreach($listDisciplinas as $did=>$dnome) {

					$classColor = $labelStatus = $media = null;
					if (isset($val[$id][6][$did]['media'])) {
						if (isset($val[$id][1][$did]['media']) && isset($val[$id][2][$did]['media'])) {
							$media = $val[$id][6][$did]['media'];

							if ($media>=7) {
								$classColor = 'info';
								$labelStatus = "\n<br/><span class='label label-{$classColor}'>Aprovado</span>";
							} elseif($media>=4 && !isset($val[$id][5][$did]['media'])) {
								$classColor = 'warning';
								$labelStatus = "\n<br/><span class='label label-{$classColor}'>Recuperação</span>";
							} else {
								$classColor = 'important';
								$labelStatus = "\n<br/><span class='label label-{$classColor}'>Reprovado</span>";
							}
						}
					}
			?> 
			<tr>
				<?php if ($w==0) { ?>
					<td colspan=2 rowspan=<?=count($listDisciplinas)?>><span class='label'><?=$aluno['registro']?></span> <?=$aluno['nome']?></td>
				<?php } ?>
				<td colspan=2><?=$dnome.$labelStatus?></td>
				<?php
					for($i=1; $i<=6; $i++) {

						$disabledMedia = $i==6 ? ' disabled' : null;
						$disabledFalta = $i==6 ? ' disabled' : null;
						$media = $falta = $faltaInputHidden = $mediaInputHidden = $classColor = null;

						if (isset($val[$id][$i][$did]['media']))
							$media = $val[$id][$i][$did]['media'];
						if (isset($val[$id][$i][$did]['falta']))
							$falta = $val[$id][$i][$did]['falta'];

						if (!is_null($media) && $_SESSION['user']['tipo']=='Professor') {
							$disabledMedia = ' disabled';
							$mediaInputHidden = "<input type='hidden' name='val[{$id}][{$i}][{$did}][media]' value='{$media}'/>";
						}

						if (!is_null($falta) && $_SESSION['user']['tipo']=='Professor') {
							$disabledFalta = ' disabled';
							$faltaInputHidden = "<input type='hidden' name='val[{$id}][{$i}][{$did}][falta]' value='{$falta}'/>";
						}

						if (!is_null($media) && !is_null($media)) {
							if ($media>=7)
								$classColor = ' info';
							elseif($media>=4 && !isset($val[$id][5][$did]['media']))
								$classColor = ' warning';
							else
								$classColor = ' error';
						}

				?> 
					<td>
					<div class="control-group<?=$classColor?>">
						<input type="text" class="input-nano" placeholder='Média' name='val[<?=$id?>][<?=$i?>][<?=$did?>][media]' id='media' max-length=3 value='<?=$media?>'<?=$disabledMedia?>><?=$mediaInputHidden?>
					</div>
					</td>
					<?php
						if ($i<>5) {
					?>
					<td>
						<input type="text" class="input-nano" placeholder='Faltas' name='val[<?=$id?>][<?=$i?>][<?=$did?>][falta]' id='faltas' max-length=3 value='<?=$falta?>'<?=$disabledFalta?>><?=$faltaInputHidden?>
					</td>
					<?php
						} else echo "<input type='hidden' name='val[{$id}][{$i}][{$did}][falta]' value=''/>";	
					?>
				<?php } ?>
			</tr>
			<?php $w++; } ?>
		<?php } ?>
		</tbody>
	</table>

	<div class='form-actions' align=center>
		<input type='submit' value='Lançar Notas!' class='btn btn-primary'>
	</div>
	<div class='msgReturn'></div>

	</form>
	<script src="assets/js/jquery.js"></script>
	<script src="assets/js/bootstrap-tooltip.js"></script>
	<script type='text/javascript'>
		$(function() {

			$('.tip').tooltip();

			/*
			 *notas, salvar
			 */
			$('form#notas').submit(function(e) {

				e.preventDefault();

				$('input[type=submit]', this).attr('disabled', 'disabled');
				$.ajax({
				 type: "POST",
				 url: $(this).attr('action'),
				 data: $(this).serialize(),
				 success: function(data) {
					$('.msgReturn').html(data);
					$('input[type=submit]').removeAttr('disabled');
				 }
				});

			});


		});
	</script>



<?php
	}
?>
