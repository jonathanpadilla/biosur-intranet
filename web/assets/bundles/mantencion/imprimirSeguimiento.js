var button_descargar_formulario = $("#button_descargar_formulario");

$(function(){

	$("#select_dia").on('change', function(){
		$("#formularios").html('<div class="row"><div class="col-md-12 text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></div></div>');

		var dia = $(this).val();

		$.ajax({
			url: Routing.generate('mantencion_ajax_cargarformularios'),
			data: {'dia': dia},
			dataType: 'html',
		}).success(function(json){
			$("#formularios").html(json);
		}).done(function(){

			button_descargar_formulario.on('click', function(){

				// var datos = $("#form_imprimir").serialize();

				// console.log(datos);

				$("#form_imprimir").submit();

			});
		
		});
		
	});

});