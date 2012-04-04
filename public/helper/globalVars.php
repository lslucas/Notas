<?php

	if (!isset($_SESSION[TP]['cpf']) || empty($_SESSION[TP]['cpf']))
		die('Você não está logado!');

	//variaveis globais
	$uid   = intval($_SESSION[TP]['id']);
	$ucpf  = $_SESSION[TP]['cpf'];
	$unasc = $_SESSION[TP]['nascimento'];
