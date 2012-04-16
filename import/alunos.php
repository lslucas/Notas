<?php

	$rp = '../';
	$_GET['p'] = 'aluno';
	$act=$res['act']='insert';
	include_once $rp.'_inc/global.php';
	include_once $rp.'_inc/db.php';
	include_once $rp.'_inc/global_function.php';
	include_once $rp.'aluno/mod.var.php';
    include_once '../_inc/Excel/reader.php';

    $excel = new Spreadsheet_Excel_Reader();
    $excel->read('alunos.xls');    


    $x=1;
	$alunos=array();
    while($x<=$excel->sheets[0]['numRows']) {
		if (isset($excel->sheets[0]['cells'][$x][1]) && !empty($excel->sheets[0]['cells'][$x][1])) {

			$nome = isset($excel->sheets[0]['cells'][$x][1]) ? $excel->sheets[0]['cells'][$x][1] : null;
			$nome = strip_tags(utf8_encode($nome));

			$nome_responsavel = isset($excel->sheets[0]['cells'][$x][2]) ? $excel->sheets[0]['cells'][$x][2] : null;
			$nome_responsavel = strip_tags(utf8_encode($nome_responsavel));

			$nome_responsavel2 = isset($excel->sheets[0]['cells'][$x][3]) ? $excel->sheets[0]['cells'][$x][3] : null;
			$nome_responsavel2 = strip_tags(utf8_encode($nome_responsavel2));

			$email = isset($excel->sheets[0]['cells'][$x][4]) ? $excel->sheets[0]['cells'][$x][4] : null;
			$email = strip_tags(utf8_encode($email));

			$email_responsavel2 = isset($excel->sheets[0]['cells'][$x][5]) ? $excel->sheets[0]['cells'][$x][5] : null;
			$email_responsavel2 = strip_tags(utf8_encode($email_responsavel2));

			$turma_nome = isset($excel->sheets[0]['cells'][$x][6]) ? $excel->sheets[0]['cells'][$x][6] : null;
			$turma_nome = strip_tags(utf8_encode($turma_nome));

			$alunos[$x]['nome'] = $nome;
			$alunos[$x]['nome_responsavel'] = $nome_responsavel;
			$alunos[$x]['nome_responsavel2'] = $nome_responsavel2;
			$alunos[$x]['email'] = $email;
			$alunos[$x]['email_responsavel'] = $email;
			$alunos[$x]['email_responsavel2'] = $email_responsavel2;
			$alunos[$x]['turma_nome'] = $turma_nome;
			$alunos[$x]['registro'] = null;
			$alunos[$x]['cpf'] = null;
			$alunos[$x]['telefone'] = null;
			$alunos[$x]['celular'] = null;
			$alunos[$x]['telefone_financeiro'] = null;
			$alunos[$x]['celular_financeiro'] = null;

		}
		/*
		  while($y<=$excel->sheets[0]['numCols']) {
			$cell = isset($excel->sheets[0]['cells'][$x][$y]) ? $excel->sheets[0]['cells'][$x][$y] : '';
			$y++;
		  }
		 */
      $x++;
	}
	//print_r($alunos);
	//grava na base
	foreach ($alunos as $int=>$res)
		include 'alunos_cadastra.php';
