<?php
	//remove itens se for setado um id
	if(isset($res['item'])) { 

		$sql_dmod = "DELETE FROM ".TABLE_PREFIX."_r_prof_disciplinas WHERE rpd_adm_id=?";
		$qry_dmod = $conn->prepare($sql_dmod);
		$qry_dmod->bind_param('i', $res['item']); 
		$qry_dmod->execute();
		$qry_dmod->close();

	}


	//insere na tabela
	$sql_atv = "INSERT INTO ".TABLE_PREFIX."_r_prof_disciplinas 
				   (rpd_adm_id, rpd_cat_id) VALUES (?, ?)";
	$qry_atv = $conn->prepare($sql_atv);

	for ($i=0;$i<count($_POST['atv_id']);$i++) {
		$qry_atv->bind_param("ii", $res['adm_id'], $_POST['disciplina'][$i]);
		$qry_atv->execute();
	}

	$qry_atv->close();
