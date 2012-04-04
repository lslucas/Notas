<?php
/*
 * TESTE
 */
$_POST = array(
	'source'=>'/cadastro.php',
	'nome'=>'Lucas Serafim',
	'email'=>'lslucas@gmail.com',
	//'cpf'=>'081.240.178-67',
	'cpf'=>'370.893.278-17',
	'rg'=>'46.548.383-X',
	'nasc_dia'=>19,
	'nasc_mes'=>'09',
	'nasc_ano'=>1990,
	'endereco'=>'Av. Brigadeiro Faria Lima',
	'numero'=>1500,
	'bairro'=>'Jardim Europa',
	'cidade'=>'São José dos Campos',
	'uf'=>'SP',
	'cep'=>'12.250-000',
	'sexo'=>'Masculino',
	'telefone'=>'(12) 9755-7829',
	'termo'=>false
);
/*
 *FIM TESTE
 */


	if (isset($_POST['source']))
		include_once 'helper/cadastro.php';

