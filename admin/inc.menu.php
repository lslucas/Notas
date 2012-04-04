 <?php

if (isset($_SESSION['user'])) { 


	$sql_niveis = "SELECT men_nivel 
			FROM ".TABLE_PREFIX."_menu 

			 WHERE men_status=1 
				AND men_nivel IS NOT NULL 
				AND EXISTS(SELECT null FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=\"".$_SESSION['user']['id']."\" AND ram_mod_id=men_modulo_id)
				OR men_status=1
				AND men_nivel IS NOT NULL
				AND ".$_SESSION['user']['id']."=1

			  GROUP BY men_nivel 
			   ORDER BY men_nivel";
	$qry_niveis = $conn->query($sql_niveis);
	$i=0;

	while($nivel = $qry_niveis->fetch_assoc()) {


	  $sql_menu_pai = "SELECT men_id,
				  men_nome,
				  men_link,
				  (SELECT mod_nome FROM ".TABLE_PREFIX."_modulo WHERE mod_id=men_modulo_id) modulo_nome 

				FROM ".TABLE_PREFIX."_menu 
				WHERE men_nivel=".$nivel['men_nivel']." 
				AND men_status=1 
				AND men_nome IS NOT NULL 
				AND EXISTS(SELECT null FROM ".TABLE_PREFIX."_r_adm_mod WHERE ram_adm_id=\"".$_SESSION['user']['id']."\" AND ram_mod_id=men_modulo_id)
				OR men_nivel=".$nivel['men_nivel']." 
				AND men_status=1 
				AND men_nome IS NOT NULL 
				AND ".$_SESSION['user']['id']."=1
				ORDER BY men_nome";
	  $qry_menu_pai = $conn->query($sql_menu_pai);
	  $j=0;



		while ($row=$qry_menu_pai->fetch_assoc()){


		  $sql_menu_filho = "SELECT men_nome,men_link FROM ".TABLE_PREFIX."_menu WHERE men_pai=".$row['men_id'].' ORDER BY men_nome';
		  $qry_menu_filho = $conn->query($sql_menu_filho);
		  $class_menu=$has_submenu='';



		if ($qry_menu_filho->num_rows>0) { 

		  $class_menu =' has-submenu';
		  $has_submenu='<img src="images/arrow-up.png" border="0" class="arrow-menu up"><img src="images/arrow-down.png" border="0" class="arrow-menu down">';
		  $menu_pai = (!empty($row['men_nome'])) ? $row['men_nome'] : $row['modulo_nome'];
		  $menu_pai = '<a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$menu_pai.' <b class="caret"></b></a>';

		} elseif (!empty($row['men_link'])) 

			  $menu_pai = (!empty($row['men_nome'])) ? '<a href="'.$row['men_link'].'">'.$row['men_nome'].'</a>' : '<a href="'.$row['men_link'].'">'.$row['modulo_nome'].'</a>';



		if ($j==0)
		  $class_menu.=' menu-top-first';


		/*
		 *html
		 */
		if ($qry_menu_filho->num_rows==0)
			echo "\n\t\t<li>{$menu_pai}</li>";

		else {

			echo "\n\t\t<li class='dropdown'>{$menu_pai}";
			echo "\n\t\t<ul class='dropdown-menu'>";

			while ($row_filho = $qry_menu_filho->fetch_assoc()){
				//<li class="divider"></li>
				//<li class="nav-header">Nav header</li>
				echo "\n\t\t\t<li><a href='{$row_filho['men_link']}'>{$row_filho['men_nome']}</a></li>";
			}

			echo "\n\t\t</ul>";

		}


	 } 

	}
	$qry_niveis->close();

}
