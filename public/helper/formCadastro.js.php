<?php
	$_POST['lang'] = basename($_SERVER['PHP_SELF'])=='cadastro.php' ? 'pt' : 'es';
	//traduções das mensagens de erro e alerta
	include_once 'lang.php';
?>
			$('.tel').mask('(99) 9999-9999');
/*
			var wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
			var fileInput = $(':file').wrap(wrapper);

			fileInput.change(function(){
				$this = $(this);
				$this.val();
				var fl = $this.val();
console.log(fl);
				var sp = fl.split('\');
console.log(sp.length);
				var filename = sp[1];
				
				$('div#filename').append('<br/>'+filename);
				//$('#file').text();
			})

			$('#file').click(function(){
				fileInput.click();
			}).show();
*/


			$('#cadastro').submit(function(e) {


				var msg = '';
				var msgm = '';

				if($('[name="nome"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_nome']?></div>';	
				if($('[name="registro"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_registro']?></div>';	
				if($('[name="departamento"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_departamento']?></div>';
				if($('[name="nome_filho"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_nome_filho']?></div>';	
				if($('[name="idade_filho"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_idade_filho']?></div>';	
				if($('[name="nome_esposa"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_nome_esposa']?></div>';	
				if($('[name="telefone_residencial"]').val()=='' && $('[name="telefone_celular"]').val()=='')
					msgm += '<div style="display:block;"><?=$_lng['empty_telefone']?></div>';	
				//if(!$(':file').val())
					//msgm += '<div style="display:block;"><?=$_lng['empty_foto']?></div>';	
				if(!$('[name="termo"]').is(':checked'))
					msgm += '<div style="display:block;"><?=$_lng['empty_termo']?></div>';	


				var msgfooter = '<br/><p align=center><input class="close" type="button" value="<?=$res['lang']=='pt' ? 'Fechar' : 'Cerrar' ?>"></p>';
				if (msgm!='') {
					//e.preventDefault();
					$('#msg').html(msgm+msgfooter);
					$('#msg').lightbox_me({
						centered: true,
						onLoad: function() { $('.close').focus(); }
					});

					$('input[type=image]').removeAttr('disabled');
					return false;
				} else
					return true;

				$('input[type=image]').attr('disabled', 'disabled');

			});
