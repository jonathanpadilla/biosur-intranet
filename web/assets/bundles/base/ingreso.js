// variables
var btn_entrar 		= $("#btn_entrar");
var form_ingreso 	= $("#form_ingreso");

$(function(){
	'use stric';

	btn_entrar.on('click', function(){
		validarIngreso();
	});
});

function validarIngreso()
{
	// console.log(new FormData(form_ingreso));
	$.ajax({
		url: form_ingreso.attr('action'),
		data: form_ingreso.serialize(),
		method: form_ingreso.attr('method'),
		dataType: 'json'
	}).success(function(json){
		if(json.result)
		{
			var nurl = Routing.generate('base_vista_homepage');
			location.href = nurl;
		}else{
			$("#alerts").html(msg('danger', 'Datos de usuario incorrectos.'));
		}
	});
}