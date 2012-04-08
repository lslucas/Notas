<?php

 $sql_field = $res['pre'].'_'.$res['col'];
 $sql_guarda = "SELECT ram_id id, (SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_id=ram_mod_id) field FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=?";
 $qry_guarda = $conn->prepare($sql_guarda);
 $qry_guarda->bind_param('i', $adm_id);
 $ok = $qry_guarda->execute()?true:false;
 $qry_guarda->bind_result($id,$field);
 $num = $qry_guarda->num_rows();

   $row_id=$row_field='';
   while($qry_guarda->fetch()) { 
    $row_id   .= $id.',';
    $row_field.= $field.',';
   }

  $row_id=substr($row_id,0,-1);
  $row['id'] = explode(',',$row_id);

  $row_field=substr($row_field,0,-1);
  $row['field'] = explode(',',$row_field);


 $qry_guarda->close();




 if(isset($_GET['verifica'])) {

/*
      if ($num==0)
       echo 'Arquivo já não existe!';

       else
	echo 'não removido';
*/
	echo $num;


  } elseif ($ok) {

     $sql_rem = "DELETE FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=?";
     $qry_rem = $conn->prepare($sql_rem);


	 #variaveis de contagem de arquivos apagados ou nao
	 $apagado = $nao_apagado = $erro_apagar = 0;

	 for($i=0;$i<count($row['id']);$i++) { 
	  if (!empty($row['id'][$i])) {


           $id  = $row['id'][$i];
           $arq = $row['field'][$i];


	     $qry_rem->bind_param('i', $id);
	     $qry_rem->execute();


		if ($qry_rem) {

		   $apagado=$apagado+1;

		} else
		  $erro_apagar = $erro_apagar+1;


	}
      } #fecha for 

	 /*
      if($apagado==1)
       echo "módulo removido!<br>";
       elseif($apagado>1) echo $apagado." módulos removidos!<br>";

      if($erro_apagar==1)
       echo "Erro ao tentar remover!<br>";
       elseif($erro_apagar>1) echo $erro_apagar." erros ao tentar remover!<br>";
	  */


     $qry_rem->close();



 } else
   echo "Não foi possível remover o(s) módulo(s)!<br>";
