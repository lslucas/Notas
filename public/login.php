<?php
/*
 * TESTE
 */
$_POST = array(
	'cpf'=>'370.893.278-17',
	'nascimento'=>'19/09/1990',
	'nasc_dia'=>19,
	'nasc_mes'=>'09',
	'nasc_ano'=>1990,
);
/*
 *FIM TESTE
 */


	if (isset($_POST['cpf']))
		include_once 'helper/login.php';

