var first 	= 0;
var max 	= 60;

$(function(){
	cargarLista();

	var win = $(window);

	$("#txt_buscar").keyup(function(){
		cargarLista();
	});

	// Each time the user scrolls
	win.scroll(function() {
		// End of the document reached?
		if ($(document).height() - win.height() == win.scrollTop()) {
			cargarLista();
		}
	});
	
});

function cargarLista()
{
	$(".div_load").show();

	// var buscar = $("#txt_buscar").val();

	$.ajax({
		url: Routing.generate('bodega_ajax_cargarlistabannos'),
		data: {'first': first, 'max': max},
		dataType: 'html'
	}).success(function(html){

		first = first + 60;

		$(".div_load").hide();
		$(".content_lista").append(html);

	});
	
}