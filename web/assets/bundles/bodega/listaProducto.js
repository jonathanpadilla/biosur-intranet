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

	$(".btn_eliminar_insumo").on('click', function(){
		var btn = $(this);
		var id = btn.data('id');

		$.ajax({
			url: Routing.generate(''),
			data: {'id': id},
			dataType: 'json',
			method: 'post',
		}).success(function(json){
			if(json.result)
			{
				location.reload(true);
			}

		});
		
	});
});