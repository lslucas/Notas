<?php

  /*
   *busca total de itens e faz variaveis de paginação
   */
  $sql_letras = "SELECT UPPER(LEFT(adm_nome, 1)) FROM ".TABLE_PREFIX."_administrador WHERE adm_tipo='Aluno' GROUP BY LEFT(adm_nome, 1) ORDER BY adm_nome";

  if($qry_letras = $conn->prepare($sql_letras)) {

    $qry_letras->execute();
    $qry_letras->bind_result($letra);

	$letras = "<div class='btn-group'>";
    if(!isset($_GET['letra']) || empty($_GET['letra'])) {
    $letras .= '<button class="btn btn-mini">Todos</button>';
    $countLetra = '';

    } else {
      $letras .= "<a class='btn btn-mini' href='?p=aluno'>Todos</a>";
      $countLetra = ' com a letra '.$_GET['letra'];
    }


      while($qry_letras->fetch()) {
        if(!isset($_GET['letra']) || $letra<>$_GET['letra'])
	        $letras .= "<a class='btn btn-mini' href='?p=aluno&letra=${letra}'>";
		else
	        $letras .= "<a class='btn btn-mini' href='javascript:void(0);'>";

        $letras .= $letra;
        $letras .= "</a>  ";
      }

    $letras = substr($letras, 0, -2);
    $qry_letras->close();

  }
  $letras .= "</div>";


  $where = ' WHERE 1';
  if( isset($_GET['letra']) && !empty($_GET['letra']) ) {
    $where.= " AND adm_tipo='Aluno' AND adm_nome LIKE '".$_GET['letra']."%' ";
  }

  if( isset($_GET['q']) && !empty($_GET['q']) ) {
    $where.= " AND (adm_nome LIKE '".$_GET['q']."%' ";
    $where.= " OR alu_registro LIKE '".$_GET['q']."%' ";
    $where.= " OR adm_email LIKE '".$_GET['q']."%' ";
	$where.= ")";
  }

/*
 *busca total de itens e faz variaveis de paginação
 */
$sql_tot = "SELECT NULL FROM ".TABLE_PREFIX."_administrador $where";
$qry_tot = $conn->query($sql_tot);
$total_itens = $qry_tot->num_rows;
$limit_end   = 30;
$n_paginas   = ceil($total_itens/$limit_end);
$pg_atual    = isset($_GET['pg']) && !empty($_GET['pg'])?$_GET['pg']:1;
$limit_start = ceil(($pg_atual-1)*$limit_end);

$qry_tot->close();


$orderby = !isset($_GET['orderby'])?$var['pre'].'_nome ASC':urldecode($_GET['orderby']);


$sql = "SELECT  ${var['pre']}_id,
		(SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=${var['pre']}_adm_id) alu_nome,
		(SELECT adm_email FROM ".TABLE_PREFIX."_administrador WHERE adm_id=${var['pre']}_adm_id) alu_email,
		${var['pre']}_registro,
		${var['pre']}_nascimento,
		${var['pre']}_cpf,
		${var['pre']}_telefone,
		${var['pre']}_celular,
		${var['pre']}_status,
		(SELECT rag_imagem FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rag_adm_id={$var['pre']}_adm_id ORDER BY rag_pos DESC LIMIT 1) imagem 
		FROM ".TABLE_PREFIX."_${var['path']} 
		INNER JOIN ".TABLE_PREFIX."_administrador
			ON adm_id={$var['pre']}_adm_id
    $where
    ORDER BY $orderby

    LIMIT $limit_start,$limit_end
    ";


 if (!$qry = $conn->prepare($sql)) {
  echo 'Houve algum erro durante a execução da consulta<p class="code">'.$sql.'</p><hr>';

  } else {

    #$sql->bind_param('s', $data); 
    $qry->execute();
    $qry->bind_result($id, $nome, $email, $registro, $nascimento, $cpf, $telefone, $celular, $status, $imagem);


    switch($total_itens) {
       case $total_itens==0: $total = 'Nenhum aluno'.$countLetra;
      break;
       case $total_itens==1: $total = "1 aluno".$countLetra;
      break;
       default: $total = $total_itens.' aluno'.$countLetra;
      break;
    }
?>
<h1><?=$var['mono_plural']?></h1>
<p class='header'></p>
<div class='small' align='right'><?=$total?></div>

<div style='display:inline-block; width:100%;'>

	<div style='float:left; margin-right: 20px; width:600px;'>
		<p class='small' style='float:left;'>
		Filtrar por &nbsp; <?=$letras?>
		</p>
	</div>

	<a href='cadastro/mod.exec_xls.php' target='_blank' class='btn btn-small' style='float:right; margin-left:20px;'>Exportar Excel</a>

	<div class="btn-group btn-small" style='float:right; padding:0px;'>
	  <button class="btn">Ordernar por</button>
	  <button class="btn dropdown-toggle" data-toggle="dropdown">
		<span class="caret"></span>
	  </button>
	  <ul class="dropdown-menu">
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_timestamp'?> ASC"<?php if($orderby==$var['pre'].'_timestamp ASC') echo ' selected';?>">Data Crescente</a></li>
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_timestamp'?> DESC"<?php if($orderby==$var['pre'].'_timestamp DESC') echo ' selected';?>">Data Decrescente</a></li>
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_nome'?> ASC"<?php if($orderby==$var['pre'].'_nome ASC') echo ' selected';?>">Nome Crescente</a></li>
		<li><a href="?p=<?=$p.$pag.$letter?>&orderby=<?=$var['pre'].'_nome'?> DESC"<?php if($orderby==$var['pre'].'_nome DESC') echo ' selected';?>">Nome Decrescente</a></li>
	  </ul>
	</div>
</div>

<table class="table table-condensed table-striped">
   <thead> 
      <tr>
<!--        <th width="5px"><input type='checkbox' name='check-all' value='1'></th>-->
        <th width="25px"></th>
        <th style='min-width:120px;'>Nome</th>
        <th width="120px">Telefone</th>
        <th width="120px">Celular</th>
      </tr>
   </thead>  
   <tbody>
<?php

    $j=0;

    while ($qry->fetch()) {


$delete_images = "&prefix=r_${var['pre']}_galeria&pre=rag&col=imagem&folder=${var['imagem_folderlist']}";


$row_actions = <<<end
<a class='tip' data-toggle='modal' href='#rm-modal{$id}' title="Clique para remover o ítem selecionado">Remover</a>
| <a class='tip' href="?p=$p&update&item=$id" title='Clique para editar o ítem selecionado'>Editar</a>
| <a class='tip status status$id' href='?p=$p&status&item=$id&noVisual' title="Clique para alterar o status do ítem selecionado" id="$id" name='$nome'>
end;

if ($status==1) 
	$row_actions .= '<font color="#000000">Ativo</font>'; 
else $row_actions .=  '<font color="#999999">Bloqueado</font>';

$row_actions .= "</a>";

?>
	<div class="modal fade" id="rm-modal<?=$id?>">
		<div class="modal-header">
			<a class="close" data-dismiss="modal">×</a>
			<h3>Remoção</h3>
		</div>
		<div class="modal-body">
		<p>Deseja remover <b><?=$nome?></b>?<div class='alert alert-warning small'>Ele será removido permanentemente!</div></p>
		</div>
		<div class="modal-footer">
			<a href="javascript:void(0);" class="btn" data-dismiss='modal'>Cancelar</a>
			<a href="index.php?p=<?=$p?>&delete&item=<?=$id?>&noVisual<?=$delete_images?>" id='<?=$id?>' class="btn-rm btn btn-danger btn-primary">Remover</a>
		</div>
	</div>
	<tr id="tr<?=$id?>">
		<td>
			<center>
				<?php 
					$arquivofull = substr($var['path_original'],0).'/'.$imagem;
					$arquivo = substr($var['path_thumb'],0).'/'.$imagem;
				?>
				<a id='ima<?=$j?>' href="<?=$arquivofull?>" class="" target='_blank' style="cursor:pointer;">
					<img src="images/lupa.gif">
				</a>
				<div id="im<?=$j?>" style="float:left;display:none">
					<?php 
						if (is_file($arquivo)) 
							echo "<img src='{$arquivo}'>";
						else 
							echo 'sem foto';
					?>
				</div>
			</center>
		</td>
		<td>
			<?=$nome?> [<?=$registro?>]
			<br/><?=$email?>
			<div class='row-actions muted small hide'><?=$row_actions?></div>
		</td>
		<td>
			<?=$telefone?>
		</td>
		<td>
			<?=$celular?>
		</td>
	</tr>
<?php
     $j++;
    }

    $qry->close();
?>
    </tbody>
    </table>
	<div id='modalDisciplina' class='modal fade hide'></div>


	  <?php
        /*
         *paginação
         */
        #$nav_cat       = isset($catid)?'&cat='.$catid:'';
		$queryString = preg_replace("/(\?|&)?(pg=[0-9]{1,})/",'',$_SERVER['QUERY_STRING']);
        $nav_cat='&'.$queryString;

	      $nav_nextclass = $pg_atual==$n_paginas?'unstyle ':'';
	      $nav_nexturl   = $pg_atual==$n_paginas?'javascript:void(0)':'?pg='.($pg_atual+1).$nav_cat;

		  echo "<div class='spacer' style='height:30px;'></div>";
	      echo "<span style='float:left'>";
	      echo "  <a href='${nav_nexturl}' class='${nav_nextclass}navbar more'>Mais ítens</a>";
	      echo "</span>";


	      echo "<span style='float:right'>";

	      $nav_prevclass = $pg_atual==1?'unstyle ':'';
	      $nav_prevurl   = $pg_atual==1?'javascript:void(0)':'?pg=1'.$nav_cat;
	
	      echo "<a href='${nav_prevurl}' class='${nav_prevclass}navbar prev'>Anterior</a>";
	

	    for($p=1;$p<=$n_paginas;$p++) {

	      $nav_class = $pg_atual<>$p?'':'unstyle ';
	      $nav_url   = $pg_atual==$p?'javascript:void(0)':'?pg='.$p.$nav_cat;
	  ?>
	  <a href='<?=$nav_url?>' class='<?=$nav_class?> navbar'><?=$p?></a>
	  <?php

	    }

	    echo "<a href='${nav_nexturl}' class='${nav_nextclass}navbar next'>Próximo</a>";
	    echo "</span>";
	  ?>
	</div>




<?php

  }
?>

