<?php
  foreach($_GET as $chave=>$valor) {
   $res[$chave] = $valor;
  }

 $sql_guarda = "SELECT (SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=alu_adm_id) alu_nome, alu_adm_id FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_id=?";
 $qry_guarda = $conn->prepare($sql_guarda);
 $qry_guarda->bind_param('i', $res['item']); 
 $ok = $qry_guarda->execute()?true:false;
 $num = $qry_guarda->num_rows();
 $qry_guarda->bind_result($nome, $adm_id); 
 $qry_guarda->fetch(); 
 $qry_guarda->close();



 if(isset($_GET['verifica'])) {

/*
      if ($num==0)
       echo 'removido';

       else
	echo 'não removido';
*/
	echo $num;


  } elseif ($ok) {


	      $sql_rem = "DELETE FROM ".TABLE_PREFIX."_${var['path']} WHERE ${var['pre']}_id=?";
	      $qry_rem = $conn->prepare($sql_rem);
	      $qry_rem->bind_param('i', $res['item']); 

        if ($qry_rem->execute()) {
          echo "<b>${nome}</b> removido com êxito!";
        }

	$qry_rem->close();

       

	# CASO EXISTA REMOVE AS IMAGENS E PDFS 
	if (file_exists($var['path'].'/mod.galeria.delete.php')) 
	 include_once $var['path'].'/mod.galeria.delete.php';
	 
 	if (file_exists($var['path'].'/mod.pdf.delete.php')) 
	 include_once $var['path'].'/mod.pdf.delete.php';
	      
 	if (file_exists($var['path'].'/mod.arquivo.delete.php')) 
	 include_once $var['path'].'/mod.arquivo.delete.php';

 	if (file_exists($var['path'].'/mod.administrador.delete.php')) 
	 include_once $var['path'].'/mod.administrador.delete.php';

 	if (file_exists($var['path'].'/mod.r_adm_mod.delete.php')) 
	 include_once $var['path'].'/mod.r_adm_mod.delete.php';

 	if (file_exists($var['path'].'/mod.r_alu_turmas.delete.php')) 
	 include_once $var['path'].'/mod.r_alu_turmas.delete.php';

 	if (file_exists($var['path'].'/mod.r_alu_notas.delete.php')) 
	 include_once $var['path'].'/mod.r_alu_notas.delete.php';

 } else
   echo "Não foi possível remover <b>${nome}</b>";
