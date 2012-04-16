<?php

	if (isset($turma_id)) {

		//insere na tabela
		$sql_atv = "INSERT INTO ".TABLE_PREFIX."_r_alu_turmas 
					   (rat_adm_id, rat_cat_id) VALUES (?, ?)";
		$qry_atv = $conn->prepare($sql_atv);
		$qry_atv->bind_param("ii", $res['adm_id'], $turma_id);
		$qry_atv->execute();
		$qry_atv->close();

	}
