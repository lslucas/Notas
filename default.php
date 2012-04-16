<?php

	include_once 'helper/default.php';

?>
<style type='text/css'>
	body {
		background-image: url('<?=SITE_URL?>/public/images/bg/colegiointegrado-transp.png');
		background-position: 340px 100px;
		background-repeat: no-repeat;
	}
</style>
	<div class="hero-unit no-print">
		<h1>Bem-vindo(a) <b><?=$_SESSION['user']['nome']?></b>!</h1>
		<!--
		<p>Lorem ipsum.</p>
		<p><a class="btn btn-primary btn-large">Learn more &raquo;</a></p>-->
	</div>

	<?php if (!empty($minhasnotas)) { ?>
	<div class="row-fluid">
		<div class="span13">
			<h2>Minhas Notas</h2>
			<div class='print'>
				<b><?=$arrAluno['nome']?></b>
				<br/><span class='label'><?=$arrAluno['registro']?></span>
			</div>
			<?=$minhasnotas?>
			<p align='right' class='no-print'>
				<a href='javascript:window.print();' class='btn'><i class='icon-print'></i> Imprimir Notas</a>
			</p>
		</div><!--/span-->
	</div><!--/fluid-->
	<?php } ?>


	<div class="row-fluid no-print">
		<?php if (!empty($meusprofessores)) { ?>
		<div class="span8">
			<h2>Professores</h2>
			<?=$meusprofessores?>
		</div><!--/span-->
		<?php } ?>
	</div><!--/row-->
