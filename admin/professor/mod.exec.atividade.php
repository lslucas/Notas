<?php
	//remove itens se for setado um id
	if(isset($res['item'])) { 

		$sql_dmod = "DELETE FROM ".TABLE_PREFIX."_r_prof_atividade WHERE rpa_adm_id=?";
		$qry_dmod = $conn->prepare($sql_dmod);
		$qry_dmod->bind_param('i', $res['item']); 
		$qry_dmod->execute();
		$qry_dmod->close();

	}


	//insere na tabela
	$sql_imod = "INSERT INTO ".TABLE_PREFIX."_r_prof_atividade 
				   (rpa_adm_id, rpa_cat_id) VALUES (?, ?)";
	$qry_imod = $conn->prepare($sql_imod);

	for ($i=0;$i<count($_POST['atv_id']);$i++) {

	  $qry_imod->bind_param("ii", $res['adm_id'], $_POST['atv_id'][$i]);
	  $qry_imod->execute();

	}

	$qry_imod->close();
