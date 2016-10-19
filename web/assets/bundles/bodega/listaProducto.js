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

	$(".btn_sacar_insumo").on('click', function(){
		var btn = $(this);
		var id = btn.data('id');
		var nombre = btn.data('nombre');

		$("#modal_nombre_insumo").text(nombre);
		$("#hidden_id").val(id);
		$("#input_cantidad").val('');
		$("#textarea_comentario").val('');
		$("#salidaProducto").modal('show');
	});

	$("#btn_guardar_salida").on('click', function(){
		var form = $("#form_salida_insumo");
		var id_i 			= $("#hidden_id").val();
		var cantidad_i 		= $("#input_cantidad").val();
		var comentario_i 	= $("#textarea_comentario").val();

		// console.log();

		$.ajax({
			url: form.attr('action'),
			data: {'id':id_i, 'cantidad':cantidad_i, 'comentario': comentario_i},
			dataType: 'json',
			method: form.attr('method')
		}).success(function(datos){
			if(datos.result)
			{
				location.reload(true);
			}
		});

	});

	$(".btn_eliminar_insumo").on('click', function(){
		var btn = $(this);
		var id = btn.data('id');

		if(confirm('¿Realmente desea eliminar el insumo?'))
		{
			$.ajax({
				url: Routing.generate('bodega_ajax_eliminarproductos'),
				data: {id: id},
				dataType: 'json',
				method: 'post'
			}).success(function(datos){
				if(datos.result)
				{
					location.reload(true);
				}
			});
		}
	});
});