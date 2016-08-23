var first 	= 0;
var max 	= 60;

$(function(){
	cargarLista();

	$("#btn_cargar_mas").on('click', function(){
		cargarLista();
	});
	
});

function cargarLista()
{
	$(".div_load").show();
	$(".div_btn_load").hide();

	// var buscar = $("#txt_buscar").val();

	$.ajax({
		url: Routing.generate('bodega_ajax_cargarlistabannos'),
		data: {'first': first, 'max': max},
		dataType: 'html'
	}).success(function(html){

		first = first + max;

		$(".div_load").hide();
		$(".div_btn_load").show();
		
		$(".content_lista").append(html);

	});
	
}