$(function(){
	$('.tip').tooltip();
	$(".alert").alert();
	//$('.tabs').button();
	//$(".collapse").collapse();
	//$('.typeahead').typeahead();

	// Acao ao clicar no botao voltar
	$('#form-back').click(function(){
		window.location='${rp}?p=${p}';
		return true;
	});


	/* MOSTRA AS ACOES AO PASSAR O MOUSE SOBRE A TR DO ÍTEM DA TABELA*/
	if ($('.table')) {
		$('.table').find('.row-actions').hide();
		$('.table tr').bind('mouseenter',function(){
			$(this).find('.row-actions').show();
		}).bind('mouseleave',function(){
			$(this).find('.row-actions').hide();
		});
	}


});
