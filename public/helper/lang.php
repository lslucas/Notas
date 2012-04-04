<?php

	/*
	 *valida lang
	 */
/*
	if (!isset($res['lang'])) {
		$res['lang'] = isset($_POST['lang']) && !empty($_POST['lang']) ? trim($_POST['lang']) : 'pt';
		$res['lang'] = $res['lang']<>'pt' && $res['lang']<>'es' ? 'pt' : $res['lang'];
	}
	//////////////////
	if ($res['lang']=='pt')
		$_lng['empty_nome'] = 'Preencha o campo do <b>nome</b> de funcionário!';
	elseif ($res['lang']=='es')
		$_lng['empty_nome'] = 'Entre con el <b>nombre</b> del empleado!';

 */


	/*
	 *mensagens dos retornos
	 */
	$_lng['empty_nome'] = 'Preencha seu <b>nome</b>!';
	$_lng['empty_source'] = 'Dev, você não informou o <b>source</b>!<br/>';
	$_lng['empty_cpf'] = 'Informe um <b>CPF</b> válido!<br/>';
	$_lng['empty_rg'] = 'Preencha o <b>RG</b>!<br/>';
	$_lng['empty_email'] = 'Informe um <b>email</b> válido!<br/>';
	$_lng['empty_nascimento'] = 'Informe uma <b>data de nascimento</b> válida!<br/>';
	$_lng['empty_endereco'] = 'Preencha seu <b>endereço</b>!<br/>';
	$_lng['empty_numero'] = 'Preencha o <b>número</b> do seu endereço!<br/>';
	$_lng['empty_bairro'] = 'Informe o seu <b>bairro</b>!<br/>';
	$_lng['empty_cidade'] = 'Informe sua <b>cidade</b>!<br/>';
	$_lng['empty_estado'] = 'Informe sua <b>estado</b>!<br/>';
	$_lng['empty_cep'] = 'Informe um <b>CEP</b>!<br/>';
	$_lng['empty_sexo'] = 'Selecione seu <b>sexo</b>!<br/>';
	$_lng['empty_telefone'] = 'Informe um <b>telefone</b>!<br/>';
	$_lng['empty_termo'] = 'Você deve aceitar os <b>termos</b> para poder se cadastrar!<br/>';
	$_lng['error_consulta'] = 'Houve um erro na tentativa de realizar a consulta de cadastro! Contate o desenvolvedor.';
	$_lng['error_cadastro'] = 'Houve um erro na tentativa de realizar o cadastro! Contate o desenvolvedor.';
	$_lng['error_pesquisa'] = 'Houve um erro na tentativa de realizar a pesquisa pelo ID! Contate o desenvolvedor.';
	$_lng['cpf_existente'] = 'O <b>CPF</b> informado já está cadastrado no sistema!';

	$_lng['coo_existente'] = 'O <b>COO - Número do Cupom Fiscal</b> informado já está cadastrado no sistema!';
	$_lng['empty_cnpj'] = 'Informe um <b>CNPJ</b> válido!<br/>';
	$_lng['empty_coo'] = 'Informe um <b>COO - Número de Cupom Fiscal</b> válido!<br/>';
	$_lng['empty_ccf'] = 'Informe um <b>CFF</b> válido!<br/>';
	$_lng['empty_uf'] = 'Informe um <b>UF</b> válido!<br/>';
	$_lng['empty_data'] = '<b>Data e hora</b> inválida!<br/>';
	$_lng['empty_valor'] = '<b>Valor</b> inválido!<br/>';
