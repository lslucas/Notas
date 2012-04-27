<?php
## NOTA: CASO EM NENHUM OUTRO MODULO SEJA DEFINIDO O ARQUIVO HEADER, ESSE SERA O ARQUIVO PADRAO
# CSS INCLUIDO NO inc.header.php
$include_css = <<<end
end;


/*
 *grafico para desempenho dos alunos
 */
$scriptInc = isset($_GET['desempenho']) ? '' : null;
$extraJS = <<<end
end;

	if (!isset($_GET['desempenho']))
		$extraJS = null;
/*
 * // fim grafico para desempenho dos alunos
 */


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
$pag = isset($_GET['pg'])?'&pg='.$_GET['pg']:'';
$letter = isset($_GET['letra'])?'&letra='.$_GET['letra']:'';
$include_js = <<<end
    <script type="text/javascript" src="${rp}js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.maskedinput-1.2.2.min.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>
	{$scriptInc}
    

<script>
  {$extraJS}
  $(function(){
      // validação do formulario, todos os campos com a classe
      // class="required" serao validados
	var container = $('completeform-error');
	// validate the form when it is submitted
	var validator = $(".form-horizontal").validate({
		errorContainer: container,
		errorClass: 'error-validate',
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate"
	});


	/*
	 *notas, salvar
	 */
	$('form#notas').submit(function(e) {

		e.preventDefault();

		$('input[type=submit]', this).attr('disabled', 'disabled');
		$.ajax({
		 type: "POST",
		 url: $(this).attr('action'),
		 data: $(this).serialize(),
		 success: function(data) {
			$('.msgReturn').html(data);
			$('input[type=submit]').removeAttr('disabled');
		 }
		});

	});


	$('#form-back-lancamento-notas').click(function() {
		window.location='index.php?p=aluno&notas';
	});


	//tabs das notas
	//$('a[data-toggle="tab"]').on('shown', function (e) {
	$('.tabNotas').on('shown', function (e) {
		var id     = $(this).attr('id');
		var target = $(this).attr('href');

		$('.temporary').hide();
		$('.loading').show();
		$.ajax({
			type: "POST",
			url: '{$p}/ajax.form.alunos.php',
			data: 'turma_id='+id,
			success: function(data){
				$('.loading').hide();
				$(target).html(data);
			}
		});

	})


	// mascara para data
	$('.phone').mask('(99) 9999-9999');
	$('#cep').mask('99.999-999');
	$('#cpf').mask('999.999.999-99');


	/* APAGA IMAGEM/ARQUIVO
	************************************/
	$(".trash-galeria").click(function(event){
	 event.preventDefault();
  	 var id_trash = $(this).attr('id');
  	 var href_trash = $(this).attr('href');

	  $.blockUI({
	   message: "<p>Tem certeza que deseja remover?</p><br><input type='submit' value='sim' id='trash-galeria-sim'> <input type='button' value='não' id='trash-galeria-nao'>"
	  });

	// ACAO AO CLICAR EM NaO
	     $("#trash-galeria-nao").click(function(){
	      $.unblockUI();
	      return false;
	     });


	// ACAO AO CLICAR EM SIM
	     $("#trash-galeria-sim").click(function(){

		// BOX DE CARREGAMENTO
		$.blockUI({
		 message: "<img src='images/loading.gif'>",
		 css: { 
               top:  ($(window).height()-24)/2+'px', 
               left: ($(window).width()-24)/2+'px', 
			   width: '24px' 
           	 } 
		});

		$.ajax({
			type: "POST",
			url: href_trash,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  
			 $('#'+id_trash).hide();
			 setTimeout(window.location.reload(), 3000);
			}
		});

	     });



	});
	/* FIM: APAGA*/




   /* LISTAGEM */


	/* APAGA 
	************************************/
	$(".btn-rm").click(function(event){
		event.preventDefault();
		var id_rm = $(this).attr('id');
		var href_rm = $(this).attr('href');

		$('.modal').modal('hide');
		// BOX DE CARREGAMENTO
		$.blockUI({
			message: "<img src='images/loading.gif'>",
			css: { 
				top:  ($(window).height()-32)/2+'px', 
				left: ($(window).width()-32)/2+'px', 
				width: '32px' 
			} 
		});

		$.ajax({
			type: "POST",
			url: href_rm,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  
			 $('#tr'+id_rm).hide();
			}
		});

	});
	/* FIM: APAGA*/


	/* STATUS 
	************************************/
	$(".status").click(function(event){
	 event.preventDefault();
  	 var id_status = $(this).attr('id');
  	 var texto_status = $(this).text();
  	 var href_status  = $(this).attr('href');
  	 var nome_status  = $(this).attr('name');

		// BOX DE CARREGAMENTO
		$.blockUI({
		 message: "<img src='images/loading.gif'>",
		 css: { 
                   top:  ($(window).height()-24)/2+'px', 
                   left: ($(window).width()-24)/2+'px', 
		   width: '24px' 
            	 } 
		});

		$.ajax({
			type: "POST",
			url: href_status,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  

			 if(texto_status=='Ativo')
			   $('.status'+id_status).html('<font color="#999999">Bloqueado</font>');

			   else
			    $('.status'+id_status).html('<font color="#000000">Ativo</font>');
			}
		});


	});
	/* FIM: STATUS*/


  });
</script>
end;
