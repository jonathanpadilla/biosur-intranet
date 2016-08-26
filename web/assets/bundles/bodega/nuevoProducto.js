$(function(){
	cargarSelect();

	$("#button_guardarproducto").on('click', function(){
		var nombre = $("#input_nombreproducto").val();

		if(nombre)
		{
			// console.log('contenido');
			$.ajax({
				url: Routing.generate('bodega_ajax_guardarproductos'),
				data: {'nombre': nombre},
				dataType: 'json',
				method: 'post',
			}).success(function(json){
				if(json.result)
				{
					$("#input_nombreproducto").val('');
					cargarSelect();
				}
			});
			
		}
		
	});

	$("#button_guardarstock").on('click', function(){
		var select_producto 	= $("#select_producto").val();
		var input_cantidad 		= $("#input_cantidad").val();
		var input_precio 		= $("#input_precio").val();
		var textarea_comentario = $("#textarea_comentario").val();

		if(select_producto && input_cantidad )
		{
			$.ajax({
				url: Routing.generate('bodega_ajax_guardarstockproductos'),
				data: {'producto':select_producto, 'cantidad': input_cantidad, 'input_precio': input_precio, 'comentario': textarea_comentario },
				dataType: 'json',
				method: 'post',
			}).success(function(json){
				if(json.result)
				{
					$("#alerts").html(msg('success', 'La información se ha guardado correctamente.'));
					
					$("#input_cantidad").val('');
					$("#textarea_comentario").val('');

				}else{
					$("#alerts").html(msg('damage', 'Error al guardar la información.'));
				}
			});
		}else{
			$("#alerts").html(msg('warning', 'Información requerida'));
		}
		
	});

});

function cargarSelect()
{
	$.ajax({
		url: Routing.generate('bodega_ajax_cargarselectproductos'),
		dataType: 'json',
	}).success(function(json){
		if(json.result)
		{
			$("#select_producto").html(json.select);
		}else{
			$("#select_producto").html('<option disabled>Sin información</option>');
		}
	});
}