<?php
# CSS INCLUIDO NO inc.header.php
$include_css = <<<end
end;


$include_js = <<<end
    <script type="text/javascript" src="js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>

<script>
  $(function(){

    // validação do formulario, todos os campos com a classe
    // class="required" serao validados
	var container = $('.completeform-error');
	// validate the form when it is submitted
	var validator = $(".form-horizontal").validate({
		errorContainer: container,
		errorClass: 'error-validate',
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate"
	});
      



	/* APAGA 
	************************************/
	$(".trash").click(function(event){
	 event.preventDefault();
  	 var id_trash = $(this).attr('id');
  	 var href_trash = $(this).attr('href');
  	 var nome_trash = $(this).attr('name');

	  $.blockUI({
	   message: "<p>Tem certeza que deseja remover <b>"+nome_trash+"</b>?</p><br><input type='submit' value='sim' id='trash-sim'> <input type='button' value='não' id='trash-nao'>"
	  });

	// ACAO AO CLICAR EM NaO
	     $("#trash-nao").click(function(){
	      $.unblockUI();
	      return false;
	     });


	// ACAO AO CLICAR EM SIM
	     $("#trash-sim").click(function(){

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
			url: href_trash,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  
			 $('#tr'+id_trash).hide();
			}
		});

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
				top:  ($(window).height()-32)/2+'px', 
				left: ($(window).width()-32)/2+'px', 
				width: '32px' 
			} 
		});

		$.ajax({
			type: "POST",
			url: href_status,
			success: function(data){
			 $.unblockUI();
			 $.growlUI(data);  

			 if(texto_status=='Ativo')
			   $('.status'+id_status).html('<span class="color-inative">Pendente</span>');

			   else
			    $('.status'+id_status).html('<span class="color-positive">Ativo</span>');
			}
		});


	});
	/* FIM: STATUS*/

  });
</script>
end;
