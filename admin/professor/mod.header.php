<?php
## NOTA: CASO EM NENHUM OUTRO MODULO SEJA DEFINIDO O ARQUIVO HEADER, ESSE SERA O ARQUIVO PADRAO


# CSS INCLUIDO NO inc.header.php
//<link href="css/reset.css" rel="stylesheet" />
$include_css = <<<end
end;


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
$pag = isset($_GET['pg'])?'&pg='.$_GET['pg']:'';
$letter = isset($_GET['letra'])?'&letra='.$_GET['letra']:'';
$include_js = <<<end
    <script type="text/javascript" src="${rp}js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.maskedinput-1.2.2.min.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>
    
    

<script>
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
      

      // mascara para data
      $('#telefone').mask('(99) 9999-9999');
      $('#celular').mask('(99) 9999-9999');
      $('#cep').mask('99.999-999');
      $('#cpf').mask('999.999.999-99');


   $('#orderby').change(function(){
     window.location.href='?p=cadastro${pag}${letter}&orderby='+$(this).val();
   });


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
