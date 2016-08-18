$(function(){
	 // agregar fila
    $("#button_agregarfilacontacto").on('click', function(){
      var last = $( "tbody#filas_contactos tr:last" ).attr('id');

      if(last)
      {
        var newr = parseInt(last) + 1;
      }else{
        var newr = 1;
      }
      

      // cargar select
      var url = Routing.generate('plugin_selects_tipocontacto');

      $.ajax({
        url: url,
        dataType: 'json',
      }).success(function(json){
        if(json.result)
        {
          cargarSelectTipoContacto(newr, json.options);
        }
      });
      
    });
    $("#filas_contactos").on('click', '.button_eliminarfilacontacto', function(){
      var button = $(this);
      var id = button.data('id');

      $("#"+id).remove();
    });


    // funciones
    var cargarSelectTipoContacto = function(newr, options)
          {
            var fila =  '<tr id="'+newr+'"><td>'+
                        '<select name="datocontacto['+newr+'][1]" id="datocontacto['+newr+'][1]" class="form-control">'+
                        '<option value="default">Seleccionar</option>'+ options +
                        '</select></td><td>'+
                        '<input type="text" name="datocontacto['+newr+'][2]" class="form-control" value="">'+
                        '</td><td>'+
                        '<input type="text" name="datocontacto['+newr+'][3]" class="form-control" value="">'+
                        '</td><td class="text-right"><div class="btn-group">'+
                        '<button type="button" data-id="'+newr+'" class="btn btn-danger button_eliminarfilacontacto">'+
                        '<i class="fa fa-trash"></i></button></div></td></tr>';

            $("#filas_contactos").append(fila);
          }
});