<h3>Desempenho de <?=$val['nome'].(!empty($val['registro']) ? ' - '.$val['registro'] : null)?></h3>
<?php

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
				WHERE ran_adm_id=?
				AND ran_turma_id=?";

	$jsonMediaDisciplina = array();
	$nota = array();
	if(!$qryr = $conn->prepare($sqlr))
		echo divAlert($conn->error);

	else {

		$lstDisciplinas = getListDisciplinas();
		$qryr->bind_param('ii', $_GET['adm_id'], $_GET['turma']);
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
			$nota[$periodo_num][$disciplina_id]['media'] = $media;
			$nota[$periodo_num][$disciplina_id]['falta'] = $falta;
		}

		$qryr->close();


		foreach ($nota as $periodo=>$arr) {
			foreach ($arr as $disciplina_id=>$row) {

				if (!isset($jsonMediaDisciplina[$disciplina_id]))
					$jsonMediaDisciplina[$disciplina_id] = null;


				if ($periodo==6) $bim = "Final";
				elseif ($periodo==5) $bim = "Recuperação";
				else $bim = $periodo."º Bim";

				//$mediaFaltaBimestre = ($row['media']+$row['falta'])/2;
				$jsonMediaDisciplina[$disciplina_id] .= "['{$bim}', {$row['media']}, {$row['falta']}],\n\t\t";

			}

		}


	}

?>
	<script type="text/javascript" src="https://www.google.com/jsapi"></script>
	<br/>
	<div class="tabbable tabs-top">
        <ul class="nav nav-tabs">
			<?php

				$w=0;
				foreach ($lstDisciplinas as $dcpId=>$dcpNome) {
					$class = $w==0 ? ' active' : null;

			?>
					<li class='<?=$class?>'><a href="#graph<?=$dcpId?>" data-toggle="tab"><?=$dcpNome?></a></li>
			<?php
					$w++;
				}
			?>
        </ul>
        <div class="tab-content">
			<?php

				$w=0;
				foreach ($lstDisciplinas as $dcpId=>$dcpNome) {
					$class = $w==0 ? ' active' : null;

			?>
			<div class="tab-pane<?=$class?>" id="graph<?=$dcpId?>">
				<script type="text/javascript">

					google.load('visualization', '1', {packages: ['corechart']});

					function drawVisualization() {
						// Some raw data (not necessarily accurate)
						var data = google.visualization.arrayToDataTable([
						  ['Bimestres', 'Média', 'Faltas'],
						  <?=substr($jsonMediaDisciplina[$dcpId], 0, -1)?>
						]);

						var options = {
						  title : 'Média e Faltas em <?=$dcpNome?>',
						  vAxis: {title: "Média", minValue: 0, maxValue: 10},
						  hAxis: {title: "Bimestres", minValue: 0, maxValue: 10},
						  seriesType: "bars",
						  width: 700,
						  height: 400
						  //,series: {2: {type: "line"}}
						};

						var chart = new google.visualization.ComboChart(document.getElementById('chart_div<?=$dcpId?>'));
						chart.draw(data, options);
					}

					google.setOnLoadCallback(drawVisualization);
				</script>
				<div id="chart_div<?=$dcpId?>" style="width: 700px; height: 400px;"></div>


				<!-- LISTA SIMPLES -->
				<center>
				<table class="table table-condensed" style='width:600px;'>
				   <thead> 
					  <tr>
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
						<th width="30px">Média</th>
						<!--<th width="30px">Faltas</th>-->
						<th width="30px">Média</th>
						<th width="30px">Faltas</th>
					  </tr>
				   </thead>  
				   <tbody>
				<?php
					$did = $dcpId;
					$dnome = $dcpNome;
					#foreach($lstDisciplinas as $did=>$dnome) {

						$classColor = $labelStatus = $media = null;
						if (isset($nota[6][$did]['media'])) {
							if (isset($nota[1][$did]['media']) && isset($nota[2][$did]['media'])) {
								$media = $nota[6][$did]['media'];

								if ($media>=7) {
									$classColor = 'info';
									$labelStatus = "\n<span class='label label-{$classColor}'>Aprovado</span>";
								} elseif($media>=4 && !isset($nota[5][$did]['media'])) {
									$classColor = 'warning';
									$labelStatus = "\n<span class='label label-{$classColor}'>Recuperação</span>";
								} else {
									$classColor = 'important';
									$labelStatus = "\n<span class='label label-{$classColor}'>Reprovado</span>";
								}
							}
						}


						echo "\n\t<tr>";
						//echo "\n\t\t<td colspan=2>{$labelStatus}</td>";

						for($i=1; $i<=6; $i++) {

							$media  = isset($nota[$i][$did]['media']) ? $nota[$i][$did]['media'] : '--';
							$falta = isset($nota[$i][$did]['falta']) ? $nota[$i][$did]['falta'] : '--';
							$classColor = null;

							if (!is_null($media) && $media<>'--') {
								if ($media>=7)
									$classColor = ' info';
								elseif($media>=4 && !isset($nota[5][$did]['media']))
									$classColor = ' warning';
								else
									$classColor = ' error';
							}

							echo "\n\t\t<td>";

							echo "<div class='control-group{$classColor}'>";
							echo "<input type='text' class='input-nano' disabled placeholder='--' value='{$media}'>";
							echo "</div>";
							echo "</td>";

							if ($i<>5) {
								echo "\n\t\t<td>";
								echo "<input type='text' class='input-nano' disabled placeholder='--' value='{$falta}'>";
								echo "</td>";
							}
						}

						echo "\n\t</tr>";
					#}

				?>
					</tbody>
				</table>
				<?=isset($labelStatus) ? $labelStatus : null?>
				</center>

			</div>
			<?php
					$w++;
				}
			?>
        </div>
	</div>
	<p align='right' class='no-print'>
		<a href='javascript:window.print();' class='btn'><i class='icon-print'></i> Imprimir</a>
		<input type='button' value='Voltar' id='form-back' class='btn'>
	</p>


