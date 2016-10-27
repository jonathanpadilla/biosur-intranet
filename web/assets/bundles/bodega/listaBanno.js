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

	}).done(function(){

		$(".btn-habilitar").on('click', function(e){
			e.preventDefault();
			var btn = $(this);
			btn.html('<i class="fa fa-cog fa-spin fa-2x fa-fw">');
			var id 	= btn.data('id');
			var estado = btn.data('estado');

			cambiarEstado(id, estado, function(n){
				if(n == 1)
				{
					btn.html('<i class="fa fa-check-circle-o fa-2x" style="color:#70ca63;"></i>');
				}else{
					btn.html('<i class="fa fa-circle-o fa-2x" style="color:#e9573f;"></i>');
				}
				btn.data('estado', n);
			});
		});

	});
	
}

function cambiarEstado(id, estado, fn)
{
	$.ajax({
		url: Routing.generate('bodega_vista_cambiarestadobannos'),
		data: {id:id, estado:estado},
		dataType: 'json',
		method: 'post',
	}).success(function(json){
		if(json.result)
		{
			fn(json.estado);
		}else{
			fn(false);
		}
	});
	
}