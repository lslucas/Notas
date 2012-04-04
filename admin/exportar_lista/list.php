   <h1><?=$var['mono_plural']?></h1>
   <p class='header'></p>
<?php

    /*
     *busca total de itens e faz variaveis de paginação
     */
    $sql_tot = "SELECT NULL FROM ".TABLE_PREFIX."_vip WHERE vip_email IS NOT NULL AND NOT EXISTS(SELECT null FROM ".TABLE_PREFIX."_cadastro WHERE cad_email=vip_email) AND vip_email<>'' AND vip_email<>'-' GROUP BY vip_email";
    $qry_tot = $conn->query($sql_tot);

    $total_itens = $qry_tot->num_rows;
    $limit_end   = 80;
    $n_paginas   = ceil($total_itens/$limit_end);
    $pg_atual    = isset($_GET['pg']) && !empty($_GET['pg'])?$_GET['pg']:1;
    $limit_start = ceil(($pg_atual-1)*$limit_end);

    $qry_tot->close();





   /*
    *consulta da lista de convidados
    */
   $sql = "SELECT vip_id,
		              vip_nome nome,
		              vip_email email,
          		    DATE_FORMAT(vip_timestamp,'%d/%m/%Y') timestamp

		   FROM ".TABLE_PREFIX."_vip
		   WHERE vip_email IS NOT NULL
       AND NOT EXISTS(SELECT null FROM ".TABLE_PREFIX."_cadastro WHERE cad_email=vip_email)
       AND vip_email<>'' AND vip_email<>'-'
       GROUP BY vip_email
       ORDER BY vip_timestamp ASC
       LIMIT $limit_start,$limit_end";

    if (!$qry = $conn->prepare($sql)) {
     echo 'Houve algum erro durante a execução da consulta<p class="code">'.$sql.'</p><hr>';

     } else {

       #$sql->bind_param('i', $agd_id); 
       $qry->execute();
       $qry->bind_result($id,$nome,$email,$timestamp);

   ?>
   <p align='right'>
    <a href='exportar_lista/mod.exec_xls.php' target='_blank' class='small'>Exportar Excel</a>
   </p>
   <?=$total_itens?> emails da lista vip que ainda não estão cadastrados no site
   <table class="list">
      <thead> 
	 <tr>
	   <th style='min-width:120px;'>Nome</th>
	   <th>E-mail</th>
	   <th width="55px"></th>
	 </tr>
      </thead>  
      <tbody>

   <?php

       $j=0;
       // Para vipa resultado encontrado...
       while ($qry->fetch()) {
   ?>


	 <tr id="tr<?=$id?>">
	   <td><?=$nome?></td>
	   <td><?=$email?></td>

	 </tr>



   <?php
	$j++;
       }

       $qry->close();
   ?>
       </tbody>
       </table>


	  <?php
        /*
         *paginação
         */
        #$nav_cat       = isset($catid)?'&cat='.$catid:'';
			  $queryString = ereg_replace("(\?\&)?(pg=[0-9])",'',$_SERVER['QUERY_STRING']);
        $nav_cat = '';


        if(!isset($_GET['pg']))
         $nav_cat.='&';

        $nav_cat.=$queryString;

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

