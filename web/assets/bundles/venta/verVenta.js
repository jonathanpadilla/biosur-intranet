// variables
var btn_detalle_mantencion = $(".btn-detalle-mantencion");
$(function(){

	// ver detalle mantencion
	btn_detalle_mantencion.on('click', function(e){
		e.preventDefault();
		$(".table_detalle_mantencion tbody").html('<tr><td colspan="5" class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></td></tr>');

		var idruta 		= $(this).data('idruta');
		var iddetalle 	= $(this).data('iddetalle');
		var fecha 		= $(this).data('fecha');

		$.ajax({
			url: Routing.generate('venta_ajax_verdetallemantencion'),
			data: {'idruta': idruta, 'iddetalle': iddetalle, 'fecha': fecha},
			dataType: 'json',
		}).success(function(json){
			if(json.result)
			{
				$(".table_detalle_mantencion tbody").html(json.lista_mantencion);
			}
		
			console.log(json);
		}).done(function(){

			$(".btn-ubicacion").on('click', function(){
				var lat = $(this).data('lat');
				var lng = $(this).data('lng');

				$('#mapaDetalle').modal('show');

				// mapa modal
				$('#mapaDetalle').on('shown.bs.modal', function (event) {

					// map.removeMarkers();

			        var map = new GMaps({
			          el: '#nmapa',
			          lat: lat,
			          lng: lng,
			          zoom: 15
			        });

			        map.addMarker({
					  lat: lat,
					  lng: lng
					});

			    });
			});

		});
		

		console.log(fecha);

		$('#detalleMantencion').modal('show');
	});

})