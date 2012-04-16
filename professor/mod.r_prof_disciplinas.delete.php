<?php
 $sql_guarda = "SELECT rpd_adm_id id FROM ".TABLE_PREFIX."_r_prof_disciplinas WHERE rpd_adm_id=?";
 $qry_guarda = $conn->prepare($sql_guarda);
 $qry_guarda->bind_param('i', $adm_id);
 $ok = $qry_guarda->execute()?true:false;
 $qry_guarda->bind_result($id);
 $num = $qry_guarda->num_rows();

   $row_id=$row_field='';
   while($qry_guarda->fetch()) { 
    $row_id   .= $id.',';
    $row_field.= $field.',';
   }

  $row_id=substr($row_id,0,-1);
  $row['id'] = explode(',',$row_id);

 $qry_guarda->close();




 if(isset($_GET['verifica'])) {

	echo $num;


  } elseif ($ok) {

     $sql_rem = "DELETE FROM ".TABLE_PREFIX."_r_prof_disciplinas WHERE rpd_adm_id=?";
     $qry_rem = $conn->prepare($sql_rem);


	 #variaveis de contagem de arquivos apagados ou nao
	 $apagado = $nao_apagado = $erro_apagar = 0;

	 for($i=0;$i<count($row['id']);$i++) { 
	  if (!empty($row['id'][$i])) {

         $id  = $row['id'][$i];
	     $qry_rem->bind_param('i', $id);
	     $qry_rem->execute();


		if ($qry_rem) {

		   $apagado=$apagado+1;

		} else
		  $erro_apagar = $erro_apagar+1;


	}
      } #fecha for 

     $qry_rem->close();



 } else
   echo "Não foi possível remover a(s) turmas(s)!<br>";
