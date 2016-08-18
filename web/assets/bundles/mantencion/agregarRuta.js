var form_ruta			= $("#form_ruta");
var button_guardarruta 	= $("#button_guardarruta");

$(function(){

	button_guardarruta.on('click', function(){

		$("#alerts").html('');
		button_guardarruta.html('<i class="fa fa-save"> </i> Guardar <i class="fa fa-cog fa-spin"></i>');

		var datos = form_ruta.serialize();

		$.ajax({
			url: Routing.generate('mantencion_ajax_guardarrura'),
			data: datos,
			dataType: 'json',
			method: form_ruta.attr('method')
		}).success(function(ajax){
			if(ajax.result)
			{
				$("#alerts").html(msg('success', 'La información se ha guardado correctamente.'));
				button_guardarruta.html('<i class="fa fa-save"> </i> Guardar</i>');
			}else{
				$("#alerts").html(msg('damage', 'Error al guardar la información.'));
				button_guardarruta.html('<i class="fa fa-save"> </i> Guardar</i>');
			}
		});


	});
});