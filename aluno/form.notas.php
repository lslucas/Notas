<h1>Lançar Notas</h1>
<p class='header'></p>
<?php

	/*
	 *query pra listar apenas as turmas do professor logado
	 */
	$innerProf = null;
	if (isset($_SESSION['user']['tipo']) && $_SESSION['user']['tipo']=='Professor') {
		$adm_id = intval($_SESSION['user']['id']);
		$innerProf = "\n\tINNER JOIN ".TABLE_PREFIX."_r_prof_turmas
							ON rpt_cat_id=rat_cat_id
							AND rpt_adm_id=?";
	}

	/*
	 *lista turmas
	 */
	$sqlt = "
	SELECT
		rat_cat_id IdTurma,
		cat_titulo Turma,
		cat_ano Ano
		,COUNT(rat_adm_id) NumAlunos
		
		FROM esc_r_alu_turmas
		INNER JOIN esc_categoria
			ON cat_id=rat_cat_id
		{$innerProf}
			
		WHERE cat_status=1
		GROUP BY rat_cat_id";

	$turmas = array();
	if($qryt = $conn->prepare($sqlt)) {

		if (!is_null($innerProf))
			$qryt->bind_param('i', $adm_id);
		$qryt->execute();
		$qryt->bind_result($id, $turma, $ano, $numAlunos);

		while ($qryt->fetch()) {
			$turmas[$id]['link'] = $id.'_'.$ano;
			$turmas[$id]['nome'] = $turma;
			$turmas[$id]['ano'] = $ano;
			$turmas[$id]['num_alunos'] = $numAlunos;
		}

		$qryt->close();

	}


	/*
	 *cabeçalho abas
	 */
	echo "<ul class='nav nav-tabs'>";

	foreach ($turmas as $id=>$turma) {
		echo "\n\t<li><a href='#{$turma['link']}' id='{$id}' data-toggle='tab'>{$turma['ano']} - {$turma['nome']} <span class='badge badge-info'>{$turma['num_alunos']}</span></a></li>";
	}

	echo "\n</ul>";



	/*
	 *conteudo abas
	 */
	echo "\n\n<div class='tab-content'>";

	echo "<div class='alert alert-info loading hide' align=center>Aguarde, carregando...</div>";
	foreach ($turmas as $id=>$turma)
		echo "\n\t\t<div class='tab-pane' id='{$turma['link']}'></div>";

	echo "\n</div>";
	echo "<div class='alert alert-info temporary' align=center>Selecione uma turma</div>";
