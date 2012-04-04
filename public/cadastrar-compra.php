<?php
/*
 * TESTE
 */
$_POST = array(
	'source'=>'/cadastro.php',
	'coo'=>rand(1, 999999),
	'ccf'=>'024FE2249291',
	'cnpj'=>'03.847.655/0001-98',
	'cidade'=>'São José dos Campos',
	'estado'=>'São Paulo',
	'compra_ano'=>2012,
	'compra_mes'=>'03',
	'compra_dia'=>'01',
	'compra_hora'=>'20:30',
	'valor'=>'R$ 11,99'
);
/*
 *FIM TESTE
 */


	if (isset($_POST['source']))
		include_once 'helper/cadastrar-compra.php';

