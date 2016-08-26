$(function(){
	'use strict';

	// estadisticas menu
	graficoMenu(1, 'menubar_bano');
	graficoMenu(2, 'menubar_caseta');
	graficoMenu(3, 'menubar_ducha');
	graficoMenu(4, 'menubar_externo');
	graficoMenu(5, 'menubar_lavamano');

	$(".link_sucursal").on('click', function(){
		var button 	= $(this);
		var id 		= button.data('id');

		$.ajax({
			url: Routing.generate('sucursal_ajax_cambiarsucursal'),
			data: {'id': id},
			dataType: 'json'
		}).success(function(json){
			if(json.result)
			{
				location.reload(true);
			}
		});
		
	});

});

function msg(tipo, mensaje)
{
	var alerta = '<div class="alert alert-'+tipo+' fade in"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'+mensaje+'</div>';
	return alerta;
}

function graficoMenu(tipo, div)
{
	$.ajax({
		url: Routing.generate('bodega_ajax_graficomenu'),
		data: {tipo: tipo},
		dataType: 'json'
	}).success(function(json){
		if(json.result)
		{
			$("#"+div+' .menubar_cantidad').html(json.arrendados+'/'+json.total);

			$("#"+div+' .menubar_porcentaje').html('('+json.porcentaje+'%)');
			$("#"+div+' .progress-bar').css('width', json.porcentaje+'%');
		}
	
		// console.log(json);
	}).done(function(){
		
	});
	
}