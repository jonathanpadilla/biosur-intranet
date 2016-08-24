$(function(){
	cargarLista();
});

function cargarLista()
{
	$.ajax({
		url: Routing.generate('cliente_ajax_cargarlista'),
		dataType: 'json',
	}).success(function(json){
		if(json.result)
		{
			$("#table_listacliente tbody").append(json.lista_cliente);
		}
	});
	
}