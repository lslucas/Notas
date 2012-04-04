 <div class='alert alert-error completeform-error hide'>
	<a class="close" data-dismiss="alert">×</a>
	Antes de prosseguir preencha corretamente o formulário e revise os campos abaixo:
	<br/><br/>
	<ol> 
		<li><label for="nome" class="error-validate">Digite o nome</label></li> 
		<li><label for="registro" class="error-validate">Informe o registro de funcionário</label></li> 
		<li><label for="departamento" class="error-validate">Entre com o departamento</label></li> 
		<li><label for="telefone_residencial" class="error-validate">Informe um <b>telefone</b></label></li> 
		<li><label for="telefone_celular" class="error-validate">Informe um <b>celular</b></label></li> 
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
    <!--<legend>Legend text</legend>-->
    <div class="control-group">
      <label class="control-label" for="foto">Foto</label>
      <div class="controls">
    	  <?php
    		  
            $num=0;
    	    if ($act=='update') {
    				  
    		    $sql_gal = "SELECT rpg_id, rpg_imagem, rpg_legenda, rpg_pos FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rpg_adm_id=? AND rpg_imagem IS NOT NULL ORDER BY rpg_pos ASC;"; 
    		    $qr_gal = $conn->prepare($sql_gal);
    		    $qr_gal->bind_param('s',$_GET['item']);
    		    $qr_gal->execute();
    		    $qr_gal->store_result();
    		    $num = $qr_gal->num_rows;
    		    $qr_gal->bind_result($g_id, $g_imagem, $g_legenda, $g_pos);
    		    $i=0;
                
    	    }
                
                if ($num>0) {
    
    		      echo '<table id="posGaleria" cellspacing="0" cellpadding="2">';
    		      while ($qr_gal->fetch()) {

					  $arquivo = $var['path_original']."/".$g_imagem;
    	  ?>
    		<tr id="<?=$g_id?>">
    		  <td width='20px' title='Clique e arraste para mudar a posição da foto' class='tip'></td>
    
    		  <td class='small'>
    		    [<a href='?p=<?=$p?>&delete_galeria&item=<?=$g_id?>&prefix=r_<?=$var['pre']?>_galeria&pre=rpg&col=imagem&folder=<?=$var['imagem_folderlist']?>&noVisual' title="Clique para remover o ítem selecionado" class='tip trash-galeria' style="cursor:pointer;" id="<?=$g_id?>">remover</a>]
    		  </td>
    		  <td>
    		    <a href='<?=$arquivo?>' id='imag<?=$i?>' class='' target='_blank'>
    			<img src='images/lupa.gif' border='0' style='background-color:none;padding-left:10px;cursor:pointer'></a> &nbsp;<!--<span style='font-size:8pt; color:#777;'><?=!empty($g_legenda) ? $g_legenda : '[sem legenda]'?></span>-->
    			 <div id='imagThumb<?=$i?>' style='float:left;display:none;'>
    			 <?php 
    			 
    			    if (file_exists(substr($var['path_thumb'],0)."/".$g_imagem))
    			     echo "<img src='".substr($var['path_thumb'],0)."/".$g_imagem."'>";
    
    			       else echo "<center>imagem não existe.</center>";
    			  ?>
    			 </div>
    		  </td>
    		</tr>
          <?php
    		      $i++;	
    
    			}
    		   echo '</table><br>';
    
    	       } else {
           ?>
    		 <div class='divImagem'>
    		   <input class="galeria" type='file' name='galeria0' id='galeria' alt='0' style="height:18px;font-size:7pt;margin-bottom:8px; width:500px;">
    		   <!--<br clear='all'/><input class="legenda" type='text' name='legenda0' id='legenda' alt='0' style="height:18px;font-size:7pt;margin-bottom:8px; width:500px;">-->
    		   <br><span class='small'>- JPEG, PNG ou GIF;<?=$var['imagemWidth_texto'].$var['imagemHeight_texto']?></span>
    		   <!--<hr noshade size=1 style='border-color:#C4C4C4; background-color:#FFF; width:520px;'/>-->
    		 </div>
    		 </p>
            <?php
            
    	       }
            ?>

        <p class="help-block">Se o professor tiver uma foto...</p>
      </div>
    </div>

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
      <label class="control-label" for="mod_id">* Atividade Profissional</label>
      <div class="controls">
	  <?php

	   if ($act=='insert') {
	    $sql_ativ = "SELECT cat_id,cat_titulo FROM ".TABLE_PREFIX."_categoria WHERE cat_status=1 AND cat_area='Atividade'";
	    $qry_ativ = $conn->prepare($sql_ativ);
	    $qry_ativ->bind_result($id, $titulo);

	    } else {
	     $sql_ativ = "SELECT cat_id, cat_titulo, (SELECT COUNT(rpa_adm_id) FROM ".TABLE_PREFIX."_r_prof_atividade WHERE rpa_cat_id=cat_id and rpa_adm_id=".$val['adm_id'].") checked FROM ".TABLE_PREFIX."_categoria WHERE cat_status=1 AND cat_area='Atividade'";
		 if (!$qry_ativ = $conn->prepare($sql_ativ))
			 echo $conn->error;
		 else
		    $qry_ativ->bind_result($id, $titulo, $checked);
	   }

	   $qry_ativ->execute();
	   
	   $i=0;
	   while ($qry_ativ->fetch()) {

	    if ($act=='update') {
	      $check[$id] = ($checked>0)?' checked':''; 

	    } else $check[$id] = '';

	  ?>
	   <label><input type='checkbox' class='required' title='Selecione ao menos um módulo' name='atv_id[]' id='atv_id' value='<?=$id?>'<?=$check[$id]?>> <?=$titulo?></label>
	  <?php $i++;} $qry_ativ->close(); ?>
           <p class="help-block">Selecione um ou mais módulos para que a pessoa tenha acesso</p>
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
        <input type="text" class="input-xlarge" placeholder='Telefone' name='telefone' id='telefone' value='<?=$val['telefone']?>'>
        <p class="help-block">Telefone principal ou telefone fixo</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="celular">Celular</label>
      <div class="controls">
        <input type="text" class="input-xlarge" placeholder='Celular' name='celular' id='celular' value='<?=$val['celular']?>'>
        <p class="help-block">Número de celular pessoal ou para trabalho</p>
      </div>
    </div>

    <div class="control-group">
      <label class="control-label" for="titulos">Títulos</label>
      <div class="controls">
        <textarea class="input-xlarge" placeholder='Títulos acadêmicos' name='titulos' id='titulos'><?=$val['titulos']?></textarea>
        <p class="help-block">Descreva todos os títulos acadêmicos do professor</p>
      </div>
    </div>


    </div>

  </fieldset>


    <div class='form-actions'>
		<input type='submit' value='ok' class='btn btn-primary'>
		<input type='button' id='form-back' value='voltar' class='btn'>
	</div>



</form>


