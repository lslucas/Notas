<?php
$msg = $administrador_email_header;
  if ($act=='insert') {
    $email_subject = SITE_NAME.": Seus dados de acesso";
    $msg .= "
	     Olá ".$res['nome'].", agora você tem dados de acesso da administração do ".SITE_NAME.":

	     <p><b>Usuário:</b> ".$res['email']."
	     <br><b>Senha:</b> ".$senha."
	     <br><b>Painel de administração:</b> <a href='".SITE_URL."' target='_blank'>".SITE_URL."</a>

	     <p>Lembrando que é possível alterar sua senha!</p>";

   } elseif ($act=='alterasenha') {
    $email_subject = SITE_NAME.": Senha alterada";
    $msg .= "
	     Olá ".$res['nome'].", sua senha foi alterada!

	     <p><b>Usuário:</b> ".$res['email']."
	     <br><b>Senha:</b> ".$senha." 
	     <br><b>Painel de administração:</b> <a href='".SITE_URL."' target='_blank'>".SITE_URL."</a>
	    ";
   } else {
    $email_subject = SITE_NAME.": Dados alterados";
    $msg .= "
	     Olá ".$res['nome'].", seus dados foram atualizados!

	     <p><b>Usuário:</b> ".$res['email']."
	     <br><b>Senha:</b> ".$senha." 
	     <br><b>Painel de administração:</b> <a href='".SITE_URL."' target='_blank'>".SITE_URL."</a>
	    ";
   } 
$msg .= $administrador_email_footer;


		/*
		 *vars to send a email
		 */
		$htmlMensage= utf8_decode($msg);
		$subject	= utf8_decode($email_subject);
		$fromEmail	= EMAIL;
		$fromName	= utf8_decode(SITE_NAME);
		$toName		= utf8_decode($res['nome']);
		$toEmail	= $res['email'];

		include_once 'inc.sendmail.header.php';

		if (!$sended)
			echo '<div class="alert alert-warning">Houve um <b>erro</b> ao enviar o email para '.$toEmail.', envie manualmente, depois entre em contato com o <a href="mailto:'.ADM_EMAIL.'">desenvolvedor</a>.</div>';
