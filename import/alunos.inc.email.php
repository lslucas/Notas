<?php
$msg = $administrador_email_header;
    $email_subject = SITE_NAME.": Seus dados de acesso";
    $msg .= "
	     Olá ".$res['nome'].", agora você tem dados de acesso ao painel do ".SITE_NAME.":

	     <p><b>Usuário:</b> ".$res['email']."
	     <br><b>Senha:</b> ".$senha."
	     <br><b>Painel:</b> <a href='".PAINEL_URL."' target='_blank'>".PAINEL_URL."</a>

	     <p>Lembrando que é possível alterar sua senha!</p>";
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
		$bccName	= isset($res['nome_responsavel2']) ? utf8_decode($res['nome_responsavel2']) : null;
		$bccEmail	= isset($res['email_responsavel2']) ? $res['email_responsavel2'] : null;


		include_once '../inc.sendmail.header.php';

		if (!$sended)
			echo '<div class="alert alert-warning">Houve um <b>erro</b> ao enviar o email para '.$toEmail.', envie manualmente, depois entre em contato com o <a href="mailto:'.ADM_EMAIL.'">desenvolvedor</a>.</div>';
