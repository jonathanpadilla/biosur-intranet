var button_guardarsucursal = $("#button_guardarsucursal");

$(function(){

	$(".select2-single").select2();

	button_guardarsucursal.on('click', function(){

		var datos = $("#form_sucursal").serialize();

		$.ajax({
			url: $("#form_sucursal").attr('action'),
			data: datos,
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