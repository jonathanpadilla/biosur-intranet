$(fucntion(){
	cargarLista();
});

function cargarLista()
{
	$.ajax({
		url: Routing.generate(''),
		data: nData,
		dataType: 'json',
		method: 'post',
	}).success(function(json){
		if(json.result)
		{
	
		}
	
		console.log(json);
	}).done(function(){
		
	});
	
}