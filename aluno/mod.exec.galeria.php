<?php

 if (isset($_FILES)) {


  include_once "_inc/class.upload.php";
   $sqlImagem = '';
   $w=$pos=0;

   $sql_smod = "SELECT rag_pos, rag_imagem FROM ".TABLE_PREFIX."_r_${var['pre']}_galeria WHERE rag_adm_id=? ORDER BY rag_pos DESC LIMIT 1";
   $qry_smod = $conn->prepare($sql_smod);
   $qry_smod->bind_param('i', $res['item']);
   $qry_smod->execute();
   $qry_smod->bind_result($pos, $imgold);
   $qry_smod->fetch();
   $qry_smod->close();
   $pos = ($pos!==0)?$pos=$pos+1:$pos;



       $sql= "INSERT INTO ".TABLE_PREFIX."_r_${var['pre']}_galeria 

		    (rag_adm_id,
		     rag_imagem,
		     rag_legenda,
		     rag_pos
		     )
		    VALUES (?, ?, ?, ?)";
       $qry=$conn->prepare($sql);
       $qry->store_result();


	   for ($i=0;$i<=count($_FILES);$i++) {

		   if (isset($_FILES['galeria'.$i]['name']) && is_file($_FILES['galeria'.$i]['tmp_name']) ) {

			$legenda = null;
			$filename = $res['item'].'_'.rand();



				$handle = new Upload($_FILES['galeria'.$i]);

				 if ($handle->uploaded) {

				   $handle->file_new_name_body  = $filename;
				   $handle->Process($var['path_original']);
				   #$handle->jpeg_quality        = 90;
				   if (!$handle->processed) echo 'error : ' . $handle->error;

				   $handle->file_new_name_body  = $filename;
				   $handle->image_resize        = true;
				   $handle->image_ratio_fill    = '#FFFFFF';
				   $handle->image_x             = $var['imagemWidth'];
				   $handle->image_y             = $var['imagemHeight'];
				   $handle->jpeg_quality        = 90;
				   $handle->process($var['path_imagem']);
				   if (!$handle->processed) echo 'error : ' . $handle->error;

				   $handle->file_new_name_body  = $filename;
				   $handle->image_resize        = true;
				   $handle->image_ratio_fill    = '#FFFFFF';
				   $handle->image_x             = $var['thumbWidth'];
				   $handle->image_y             = $var['thumbHeight'];
				   $handle->jpeg_quality        = 90;
				   $handle->process($var['path_thumb']);
				   if (!$handle->processed) echo 'error : ' . $handle->error;

					$imagem = $handle->file_dst_name;


				 $qry->bind_param('issi', $res['item'], $imagem, $legenda, $pos); 
				 $qry->execute();

				}

		  }

		$pos++;

	   }


   $qry->close();


 }
