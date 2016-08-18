// variables
var map;

$(function(){

	$("#sortable").sortable({
		stop: function(event, ui) {

			var result  = $(this).sortable('toArray', {id: 'id'});
			var nUrl	= Routing.generate('mantencion_ajax_guardarordenrura');

			// console.log(result);
		    
		    $.ajax({
		    	url: nUrl,
		    	data: {orden:result},
		    	dataType: 'json',
		    }).success(function(json){
		    	if(json.result)
		    	{
		    		cargarRuta();
		    	}
		    });
		    
		  }
	});

});

function initialize()
{
	cargarRuta();
}

function cargarRuta()
{
	var nUrl 	= Routing.generate('mantencion_ajax_cargarrura');
	var nData 	= {dia: $("#dia").data('dia'), camion: $("#camion").data('camion')};

	$.ajax({
		url: nUrl,
		data: nData,
		dataType: 'json',
	}).success(function(json){
		if(json.result)
		{
			dibujarRuta(json.ruta);
		}
	
		// console.log(json);
	}).done(function(){
		
	});
	
}

function dibujarRuta(ruta)
{
	// marcar puntos de ruta
	var locations = [];
	var nruta = [new google.maps.LatLng(-37.4137714, -72.4080185)];
	$.each(ruta, function(key, value){
		var texto = '<p><strong>Cliente: </strong>'+value.venta_cliente+'</p>'+
					'<p><strong>Direccion: </strong>'+value.detalle_direccion+', '+value.detalle_ciudad+'</p>'+
					'<p><strong>Productos: </strong>'+
					((value.detalle_cbano != 0)? "Baños "+value.detalle_cbano+", ": "")+
					((value.detalle_ccaseta != 0)? "Casetas "+value.detalle_ccaseta+", ": "")+
					((value.detalle_cducha != 0)? "Duchas "+value.detalle_cducha+", ": "")+
					((value.detalle_cexterno != 0)? "Externos "+value.detalle_cexterno+", ": "")+
					((value.detalle_clavamano != 0)? "Lavamanos "+value.detalle_clavamano: "")+
					'</p>';

		locations.push([texto, value.lat, value.lon]);
		nruta.push(new google.maps.LatLng(value.lat, value.lon));
	});

	// opciones
	var myOptions = {    
		// center: new google.maps.LatLng(-37.4211704, -72.4040959),  
		// zoom: 12,     
		mapTypeId: google.maps.MapTypeId.ROADMAP
	};

	// iniciar mapa
	map = new google.maps.Map(document.getElementById("mapa"), myOptions);

	var bounds = new google.maps.LatLngBounds();
	var infowindow = new google.maps.InfoWindow();

	var image = {
        url: $("#data_map").data('icon'),
        // This marker is 20 pixels wide by 32 pixels high.
        size: new google.maps.Size(26, 51),
        // The origin for this image is (0, 0).
        origin: new google.maps.Point(0, 0),
        // The anchor for this image is the base of the flagpole at (0, 32).
        anchor: new google.maps.Point(10, 40)
    };

	for (i = 0; i < locations.length; i++) {
    	marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            animation: google.maps.Animation.DROP,
            icon: image,
            map: map
        });

        bounds.extend(marker.position);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));

    }

    marker = new google.maps.Marker({
	    position: {lat:-37.4137714, lng:-72.4080185},
	    map: map,
	    title: 'Hello World!'
	});

    map.fitBounds(bounds);

    // dibujar ruta
    // lineas.remove();
	var lineas = new google.maps.Polyline({        
		path: nruta,
		map: map, 
		strokeColor: 'green', 
		strokeWeight: 4,  
		strokeOpacity: 1, 
		clickable: false
	});
}