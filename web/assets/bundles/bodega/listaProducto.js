$(function(){
	$(".btn_historial").on('click', function(){
		var id = $(this).data("id");

		$.ajax({
			url: Routing.generate('bodega_ajax_verhistorialproductos'),
			data: {'id': id},
			dataType: 'json',
		}).success(function(json){
			if(json.result)
			{
				$(".table_historial tbody").html(json.lista);
				
			}else{
				$(".table_historial tbody").html('<tr><td colspan="5">Sin Información</td></tr>');

			}
		
			$("#historialProducto").modal('show');
		});

	});	
});