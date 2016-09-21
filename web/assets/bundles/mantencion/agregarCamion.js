$(function(){
	$("#btn_guardar_asignacion").on('click', function(){
		var btn = $(this);
		btn.html('<i class="fa fa-save"> </i> Guardar <i class="fa fa-cog fa-spin"></i>');

		$.ajax({
			url: $("#form_asignacion").attr('action'),
			data: $("#form_asignacion").serialize(),
			dataType: 'json',
			method: 'post'
		}).success(function(json){
			if(json.result)
			{
				$("#alerts").html(msg('success', 'La información se ha guardado correctamente.'));
				btn.html('<i class="fa fa-save"> </i> Guardar');
			}else{
				$("#alerts").html(msg('damage', 'Error al guardar la información.'));
				btn.html('<i class="fa fa-save"> </i> Guardar');
			}

		});

	});

	$("#button_guardarcamion").on('click', function(){

		var patente = $("#input_patente").val();

		if(patente)
		{
			$.ajax({
				url: Routing.generate('mantencion_ajax_agregarcamion'),
				data: {'patente': patente},
				dataType: 'json',
				method: 'post'
			}).success(function(json){
				if(json.result)
				{
					location.reload();
				}
			});
		}

	});
});