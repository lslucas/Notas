<?php
  
  foreach($_GET as $chave=>$valor) {
   $res[$chave] = $valor;
  }

 $sql_guarda = "SELECT (SELECT adm_nome FROM ".TABLE_PREFIX."_administrador WHERE adm_id=prof_adm_id) prof_nome,${var['pre']}_status FROM ".TABLE_PREFIX."_${var['path']}";
 $sql_guarda.= " WHERE ${var['pre']}_id=?";
 if (!$qry_guarda = $conn->prepare($sql_guarda))
	 echo $conn->error;

 else {

	 $qry_guarda->bind_param('i', $res['item']); 
	 $ok = $qry_guarda->execute()==true?true:false;
	 $num = $qry_guarda->num_rows();
	 $qry_guarda->bind_result($nome,$status); 
	 $qry_guarda->fetch(); 
	 $qry_guarda->close();


	 if ($ok) {

		 $novoStatus  = $status==1?0:1;
		 $novoStatusT = $status==1?'Bloqueado':'Ativo';
		 $sql_status  = "UPDATE ".TABLE_PREFIX."_${var['path']} SET ${var['pre']}_status=${novoStatus}";
		 $sql_status .= " WHERE ${var['pre']}_id=?";
		 $qry_status  = $conn->prepare($sql_status);
		 $qry_status->bind_param('s', $res['item']); 

			 if ($qry_status->execute()) {
				echo "<b>${nome}</b> agora está <b>${novoStatusT}</b>";
		 }

	   $qry_status->close();

	 }


 }
