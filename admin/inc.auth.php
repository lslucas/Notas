<?php
 #inicia a sessao
 session_start();

	#se houver um post da pagina de login
	if (isset($_POST['username'])) {

		$user = $_POST['username'];
		$hash = md5($_POST['password']);


		$sql_var = "SELECT adm_id,adm_nome,adm_email,adm_senha FROM ".TABLE_PREFIX."_administrador WHERE adm_status=1 AND adm_email=?";
		$qry_var = $conn->prepare($sql_var);
		$qry_var->bind_param('s',$user);
		$qry_var->execute();
		$qry_var->bind_result($aid,$anome,$aemail,$asenha);
		$qry_var->fetch();
		$qry_var->close();



		if ( $asenha<>$hash ) { 

		 $noMenu = $noLog = 1; 
		 $classNotice = 'error';
		 $msgNotice = "Usuário ou senha invalido! Tente novamente ou entre em contato com o ";
		 $msgNotice.= "<a href=\"mailto:".ADM_EMAIL."?subject=Login inválido\">administrador</a>.";



  	             } elseif (empty($user) || empty($hash)) {

		       $noMenu = $noLog = 1; 
		       $msgError = "Preencha <b>todos</b> os campos antes de prosseguir!";


	 	    		#usuario ou senha inválidos
			} else {

			    $userdata = array(
				    'id' => $aid,
				    'nome' => $anome,
				    'email' => $aemail,
				    'senha' => $asenha,
				    'ip' => $_SERVER['REMOTE_ADDR'],
				    'host' => gethostbyaddr($_SERVER['REMOTE_ADDR']),
				    'useragent' => $_SERVER['HTTP_USER_AGENT']
			    );
			    $_SESSION['user'] = $userdata;

			    $reff = (basename($_SERVER['HTTP_REFERER'])<>$rp.'/') ? $_SERVER['HTTP_REFERER'] : 'principal.php';
			    header("Location: ".$reff);  
			    die();



			}


	 } else {

	  	 #CHECA AS PERMISSOES DO USUÁRIO
		 function hasAccess() {
			 global $conn,$p,$sess,$msgNotice,$classNotice,$noMenu,$err;

			 $add=0;
			 if (!isset($_SESSION['user']) && !isset($_GET['act'])) {
			   $noMenu=1;
			   #$classNotice = 'error';
			   #$msgNotice   = 'Você não está logado ou sua sessão expirou!';
			   
			 } 
			 if (!isset($_SESSION['user']) && isset($_GET['act']) && $_GET['act']=='logout') {
			   $noMenu=1;
			   $classNotice = 'success';
			   $msgNotice   = "Você não está mais logado!";
			 }



			if (isset($p) && !empty($p) && !isset($err)) {

				$sql_cat = "SELECT null,mod_id
						FROM ".TABLE_PREFIX."_r_adm_mod 
					  	  INNER JOIN ".TABLE_PREFIX."_modulo
						    ON ram_mod_id=mod_id
					 	WHERE ram_adm_id=?
					  	  AND mod_nome=?
					";
				$qr_cat = $conn->prepare($sql_cat);
				$qr_cat->bind_param('is',$_SESSION['user']['id'],$p);
				$qr_cat->execute();
				$num_cat = $qr_cat->num_rows();
				$qr_cat->close();

				$add = $add+$num_cat;
				if ( isset($p) && $p=='alterar_senha') $add=1;

				if ($add==0) {
			  	 $msgError = '<center>Você não tem permissão para acessar essa página, consulte o <a href="mailto:'.ADM_EMAIL.'?subject='.SITE_NAME.': Permissões ['.$_SESSION['user']['email'].']">administrador</a> para maiores informações.</center>';
				}
				
		  	     }

				 if ($add==0) return false;
						else return true;
		 }



/*
	 if ( $sess->isSetVar('adm_email') && basename($_SERVER['PHP_SELF'])<>'index.php') 
		die(header("Location: ".$rp."index.php"));

  if (basename($_SERVER['PHP_SELF'])=='index.php' && $sess->isSetVar('adm_email'))
 	  header("Location: ".$rp."principal.php");

*/		

		# funcao de login
		hasAccess();
		logquery();
/*
	       if (isset($msgError)) {
		 if (basename($_SERVER['PHP_SELF'])<>'index.php' || basename($_SERVER['PHP_SELF'])=='index.php' && !isset($_GET['act'])){
		   die(header("Location: ".$rp."index.php?act=err"));
		 }
		}
*/


 }
?>
