<?php

	$sqlt = "SELECT 
			cat_id,
			cat_titulo turma,
			cat_ano anoturma,
			(SELECT COUNT(rat_adm_id) FROM ".TABLE_PREFIX."_r_alu_turmas WHERE rat_cat_id=cat_id) num
			FROM ".TABLE_PREFIX."_categoria
				WHERE cat_status=1 AND cat_area='Turmas'
					ORDER BY cat_ano DESC, cat_titulo";


	$turmas = array();
	if($qryt = $conn->prepare($sqlt)) {

		$qryt->execute();
		$qryt->bind_result($id, $turma, $ano, $numAlunos);

		while ($qryt->fetch()) {
			$turmas[$id]['link'] = linkfySmart($ano.' '.$turma);
			$turmas[$id]['nome'] = $turma;
			$turmas[$id]['ano'] = $ano;
			$turmas[$id]['num_alunos'] = $numAlunos;
		}

		$qryt->close();

	}


	/*
	 *cabeçalho abas
	 */
	echo "<ul class='nav nav-tabs'>";

	foreach ($turmas as $id=>$turma) {
		echo "\n\t<li><a href='#{$turma['link']}' data-toggle='tab'>{$turma['ano']} - {$turma['nome']} <span class='badge'>{$turma['num_alunos']}</span></a></li>";
	}

	echo "\n</ul>";



	/*
	 *conteudo abas
	 */
	echo "\n\n<div class='tab-content'>";

	$i=0;
	foreach ($turmas as $id=>$turma) {
		$active = $i==0 ? ' active' : null;
		echo "\n\t\t<div class='tab-pane{$active}' id='{$turma['link']}'>{$turma['nome']}</div>";
		$i++;
	}

	echo "\n</div>";
?>
<div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="nome" class="error-validate">Digite o nome</label></li> 
	</ol> 
</div>



<form method='post' action='?<?=$_SERVER['QUERY_STRING']?>' id='form_<?=$p?>' class='form-horizontal cmxform' enctype="multipart/form-data">
 <input type='hidden' name='act' value='<?=$act?>'>
<?php
  if ($act=='update') {
    echo "<input type='hidden' name='item' value='${_GET['item']}'>";
    echo "<input type='hidden' name='adm_id' value='${val['adm_id']}'>";
  }
?>

<h1>
<?php 
  if ($act=='insert') echo $var['insert'];
   else echo $var['update'];
?>
</h1>
<p class='header'>Todos os campos com <b>- * -</b> são obrigatórios.</p>


  <fieldset>
    <div class="control-group">
      <label class="control-label" for="registro">Número de Registro</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='registro' name='registro' id='nome' value='<?=$val['registro']?>'>
        <p class="help-block">Número de registro para identificação</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="nome">* Nome</label>
      <div class="controls">
        <input type="text" class="input-xlarge required" placeholder='Nome' name='nome' id='nome' value='<?=$val['nome']?>'>
        <p class="help-block">Informe o nome do professor</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="email">* Email</label>
      <div class="controls">
        <input type="text" class="input-xlarge email required" placeholder='email@provedor.com.br' name='email' id='email' value='<?=$val['email']?>'>
        <p class="help-block">Informe um email válido da pessoa</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="cpf">CPF</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='CPF' name='cpf' id='cpf' value='<?=$val['cpf']?>'>
        <p class="help-block">CPF do professor</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="rg">RG</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='RG' name='rg' id='rg' value='<?=$val['rg']?>'>
        <p class="help-block">RG do professor</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="telefone">Telefone</label>
      <div class="controls">
        <input type="text" class="input-xlarge phone" placeholder='Telefone' name='telefone' id='telefone' value='<?=$val['telefone']?>'>
        <p class="help-block">Telefone principal ou telefone fixo</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="celular">Celular</label>
      <div class="controls">
        <input type="text" class="input-xlarge phone" placeholder='Celular' name='celular' id='celular' value='<?=$val['celular']?>'>
        <p class="help-block">Número de celular pessoal ou para trabalho</p>
      </div>
    </div>
  </fieldset>

  <fieldset>
    <legend>Dados para Financeiro</legend>
    <div class="control-group">
      <label class="control-label" for="nome_financeiro">Nome</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Nome do responsável financeiro' name='nome_financeiro' id='nome_financeiro' value='<?=$val['nome_financeiro']?>'>
        <p class="help-block">Informe o nome do responsável financeiro deste aluno</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="email_financeiro">Email</label>
      <div class="controls">
        <input type="text" class="input-xlarge email" placeholder='Email do responsável financeiro' name='email_financeiro' id='email_financeiro' value='<?=$val['email_financeiro']?>'>
        <p class="help-block">Informe um email válido</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="telefone_financeiro">Telefone</label>
      <div class="controls">
        <input type="text" class="input-xlarge phone" placeholder='Telefone do responsável financeiro' name='telefone_financeiro' id='telefone_financeiro' value='<?=$val['telefone_financeiro']?>'>
        <p class="help-block">Informe um telefone principal para contato</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="celular_financeiro">Celular</label>
      <div class="controls">
        <input type="text" class="input-xlarge phone" placeholder='Celular do responsável financeiro' name='celular_financeiro' id='celular_financeiro' value='<?=$val['celular_financeiro']?>'>
        <p class="help-block">Informe um celular para contato</p>
      </div>
    </div>
  </fieldset>


    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>



</form>
