<?php
## NOTA: CASO EM NENHUM OUTRO MODULO SEJA DEFINIDO O ARQUIVO HEADER, ESSE SERA O ARQUIVO PADRAO


# CSS INCLUIDO NO inc.header.php
//<link href="css/reset.css" rel="stylesheet" />
$include_css = <<<end
     <link rel="stylesheet" type="text/css" href="${rp}js/jGrowl-1.2.4/jquery.jgrowl.css"/>
     <link rel="stylesheet" href="${rp}js/bettertip/jquery.bettertip.css" type="text/css" />
     <style>
       div.growlUI { 
	background: url(${rp}images/warning.png) no-repeat;
	height:50px;
       } div.growlUI h1, div.growlUI h2 {
	color: white;
	padding: 5px 5px 5px 60px;
	text-align: left;
	font-family:'Tahoma';
       } td.showDragHandle {
	background-image: url(${rp}images/drag.gif);
	background-repeat: no-repeat;
	background-position: center center;
	cursor: move;

      }.tDnD_whileDrag {
	background-color: #eee;
      }
     </style>
end;


# JS INCLUIDO NO inc.header.php, também pode conter codigo js <script>alert();</script>
/*
<script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript" src="js/jquery.tipTip.js"></script>
*/
$include_js = <<<end
    <script type="text/javascript" src="${rp}js/bettertip/jquery.bettertip.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.blockUI.js"></script>
    <script type="text/javascript" src="${rp}js/jGrowl-1.2.4/jquery.jgrowl.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.maskedinput-1.2.2.min.js"></script>
    <script type="text/javascript" src="${rp}js/jquery.validate.min.js"></script>
    
    

<script>
  $(function(){
      // validação do formulario, todos os campos com a classe
      // class="required" serao validados
	var container = $('div.container-error');
	// validate the form when it is submitted
	var validator = $(".form").validate({
		errorContainer: container,
		errorClass: 'error-validate',
		errorLabelContainer: $("ol", container),
		wrapper: 'li',
		meta: "validate"
	});
      

      // mascara para data
      $('#nascimento').mask('99/99/9999');
      $('#cep').mask('99.999-999');
      $('#telefone').mask('(99) 9999-9999');



   /* LISTAGEM */


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
			 $.growlUI('Remoção',data);  
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
			 $.growlUI('Status',data);  

			 if(texto_status=='Ativo')
			   $('.status'+id_status).html('<font color="#999999">Bloqueado</font>');

			   else
			    $('.status'+id_status).html('<font color="#000000">Ativo</font>');
			}
		});


	});
	/* FIM: STATUS*/



	/* MOSTRA AS ACOES AO PASSAR O MOUSE SOBRE A TR DO ÍTEM DA TABELA*/
	$('.list tr').bind('mouseenter',function(){
	 $(this).find('.row-actions').css('visibility','visible');
	}).bind('mouseleave',function(){
	 $(this).find('.row-actions').css('visibility','hidden');
	});
  });
</script>
end;

?>
