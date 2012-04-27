<?php

	//força login caso nao esteja logado
	if ((!isset($_SESSION['user']) || empty($_SESSION['user']['id'])) && !empty($p)) {
		include_once 'inc.logout.php';
	}

	if (isset($rp))
		$_SESSION['rp'] = $rp;

	if (isset($p))
		$_SESSION['p'] = $p;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
	<title><?=$pg_title?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Lucas Serafim - lucasserafim.com.br">
    <!-- Le styles -->
    <link href="<?=$rp?>bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$rp?>css/application.css" rel="stylesheet">
    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="<?=$rp?>assets/ico/favicon.ico">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="<?=$rp?>assets/ico/apple-touch-icon-114-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="<?=$rp?>assets/ico/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="<?=$rp?>assets/ico/apple-touch-icon-57-precomposed.png">

    <!-- STYLESHEETS -->
    <?php 
	if (isset($include_css)) 
		echo $include_css;
    ?>
  </head>
  <body>
	<div class="navbar no-print">
		<div class="navbar-inner">
		  <div class="container">
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			  <span class="icon-bar"></span>
			</a>
			<a class="brand" href="<?=$rp?>index.php"><?=SITE_NAME?></a>
			<div class="nav-collapse">
			  <ul class="nav">
				<li<?=!isset($p) || empty($p) ? ' class="active"' : null?>><a href="<?=$rp?>index.php">Home</a></li>
				<?php include_once 'inc.menu.php'; ?>
			  </ul>
			  <?php /*if (isset($_SESSION['user'])) { ?>
			  <form class="navbar-search pull-left" action="">
				<input type="text" class="search-query span2" placeholder="Busca">
			  </form>
			  <?php }*/ ?>
			  <ul class="nav pull-right">
				<li class="divider-vertical"></li>
				<?php 
				  if (isset($_SESSION['user'])) { 
					$user_p_update = strtolower($_SESSION['user']['tipo']);
				?>
				<li class="dropdown">
				  <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$_SESSION['user']['nome']?> <b class="caret"></b></a>
				  <ul class="dropdown-menu">
					<li><a href='<?=$rp?>index.php?p=<?=$user_p_update?>&update' class='tip' title='Clique para editar informações de cadastro'>Altualizar dados</a></li>
					<li><a href='<?=$rp?>index.php?p=<?=$user_p_update?>&alterasenha' class='tip' title='Clique para alterar a senha'>Alterar senha</a></li>
					<li class="divider"></li>
					<li><a href="<?=$rp?>logout.php">Sair</a></li>
				  </ul>
				</li>
				<?php 

					}
				?>
			  </ul>
			</div><!-- /.nav-collapse -->
		  </div>
		</div><!-- /navbar-inner -->
	</div>

	<center>
	<div class="span14 nospan column-container">
