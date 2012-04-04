<?php

	session_start();
	$rp			= 'admin/';
	$_GET['p']  = 'cadastro';
	include_once $rp.'_inc/global.php';

	$_SESSION[TP] = array();
	session_destroy();

	echo 'Você saiu!';
