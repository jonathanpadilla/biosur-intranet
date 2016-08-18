// variables
var btn_enviar 	= $("#btn_enviar");
var username	= $("#username");

$(function(){
	btn_enviar.on('click', function(){

		var rut = username.val();
		if(rut)
		{
			$.ajax({
				url: Routing.generate('base_ajax_enviarsolicitud'),
				data: {rut: rut},
				dataType: 'json'
			}).success(function(json){
				if(json.result)
				{
					username.val("");
					$("#alerts").html(msg('success', json.msg));
				}else{
					$("#alerts").html(msg('danger', json.msg));
				}
				console.log(json);
			});
			
		}
	});
});