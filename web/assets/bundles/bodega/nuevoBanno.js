// variables
var button_guardarbanno = $("#button_guardarbanno");
var form_banno 			= $("#form_banno");
var alert_success		= $("#alert_success");
var select_producto 	= $("#select_producto");
var select_tipoproducto = $("#select_tipoproducto");

$(function(){

	button_guardarbanno.on('click', function(){
		guardarBanno()
	});

	var producto = select_producto.val();
	cargarTipo(producto);
	select_producto.on('change', function(){
		var nproducto = select_producto.val();
		cargarTipo(nproducto);
	});

});

function guardarBanno()
{
	$("#alerts").html('');

	$.ajax({
		url: form_banno.attr('action'),
		dataType: 'json',
		data: form_banno.serialize(),
		method: form_banno.attr('method')
	}).success(function(json){
		if(json.result)
		{
			$("#alerts").html(msg('success', 'La información se ha guardado correctamente.'));
			location.reload();
		}else{
			$("#alerts").html(msg('damage', 'Error al guardar la información.'));
		}
		
	});
}

function cargarTipo(producto)
{
	var select = select_tipoproducto.data('select');
	
	$.ajax({
		url: Routing.generate('bodega_ajax_cargartipobanno'),
		data: {select:select, producto: producto},
		dataType: 'json'
	}).success(function(json){
		if(json.result)
		{
			select_tipoproducto.html(json.options);
		}else{
			select_tipoproducto.html('<option value="default">Sin información</option>');
		}
	});
}