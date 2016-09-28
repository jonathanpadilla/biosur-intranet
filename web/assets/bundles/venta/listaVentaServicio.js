$(function(){
	cargarLista();

});

function cargarLista()
{
	$("#table_venta tbody").html('<tr><td colspan="6" class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></td></tr>');
	$.ajax({
		url: Routing.generate('venta_ajax_cargarlistaventaservicio'),
		dataType: 'json'
	}).success(function(json){
		if(json.result)
		{	
			$("#table_venta tbody").html(json.cargarlista);
		}else{
			$("#table_venta tbody").html('<tr><td colspan="6">Sin información</td></tr>');
			console.log('error');
		}
		
	}).done(function(){

		// botones pulsate
		$(".pulsate").pulsate();

		// tooltips
		$('[data-toggle="tooltip"]').tooltip();

		$(".button_eliminarVenta").on('click', function(){

			if(confirm('¿Realmente desea finalizar el arriendo?'))
			{
				// variables
				var button = $(this);
				var id = button.data('id');

				$.ajax({
					url: Routing.generate('venta_ajax_finalizararriendo'),
					data: {'id':id},
					dataType: 'json',
					method: 'post',
				}).success(function(json){
					if(json.result)
					{
						cargarLista();
					}
				
				}).done(function(){
					
				});
			}

			
		});

	});
}