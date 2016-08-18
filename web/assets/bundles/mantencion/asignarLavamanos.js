// variables
var check_candado 			= $(".check_candado");
var button_lavamanos 		= $(".button_lavamanos");
var button_quitarasignacion = $(".button_quitarasignacion");

$(function(){

	// check candado
	check_candado.on('click', function(){
		var check 		= $(this);
		var id 			= check.data('id');
		var candado;
		var nUrl 		= Routing.generate('mantencion_ajax_guardarcandado');

		if(check.prop('checked'))
		{
			candado = 1;
		}else{
			candado = 0;
		}

		$.ajax({
			url: nUrl,
			data: {id: id, candado: candado},
			dataType: 'json',
		}).success(function(json){
			if(!json.result)
			{
				console.log('error');
			}
		}).done(function(){
			
		});
		

	});

	// asignar lavamanos
	button_lavamanos.on('click', function(){

		$("#table_lavamanos tbody").html('<tr><td colspan="6" class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></td></tr>');

		var button  	= $(this);
		var id 			= button.data('id');
		var lavamanos 	= button.data('lavamanos');
		var contrato 	= button.data('contrato');
		var banno 		= button.data('banno');
		var nUrl 		= Routing.generate('mantencion_ajax_asignacionlavamano');

		$.ajax({
			url: nUrl,
			data: {id: id, lavamanos:lavamanos, contrato:contrato, banno:banno },
			dataType: 'json',
		}).success(function(json){
			if(json.result)
			{
				$("#table_lavamanos tbody").html(json.cargarLista);
			}else{
				$("#table_lavamanos tbody").html('<tr><td colspan="3">Sin información</td></tr>');
			}
		}).done(function(){

			$(".button_asignar").on('click', function(){

				var btnasignar = $(this);
				var lavamano 	= btnasignar.data('lavamano');

				$.ajax({
					url: Routing.generate('mantencion_ajax_guardarasignacionlavamano'),
					data: {id:id, lavamano:lavamano},
					dataType: 'json',
				}).success(function(json){
					if(json.result)
					{
						button.html(json.lavamanos);
						button.removeClass('btn-warning');
						button.addClass('btn-info');

						$("#modal_lavamanos").modal('hide');
					}
				});
				
			});

		});
		
	});

	// quitar asignacion
	button_quitarasignacion.on('click', function(){
		var button 	= $(this);
		var id 		= button.data('id');

		$.ajax({
			url: Routing.generate('mantencion_ajax_eliminarasignacionlavamano'),
			data: {id:id},
			dataType: 'json',
		}).success(function(json){
			if(json.result)
			{
				$('.button_lavamanos[data-id="'+id+'"]').html('Seleccionar');
				$('.button_lavamanos[data-id="'+id+'"]').removeClass('btn-info');
				$('.button_lavamanos[data-id="'+id+'"]').addClass('btn-warning');
			}
		
			console.log(json);
		});
		
	});


});