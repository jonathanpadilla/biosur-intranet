// variables
var button_guardarasignacion  = $("#button_guardarasignacion");
var form_asignarbannos        = $("#form_asignarbannos");
$(function(){
	// lista doble
	var demo1 = $('.select_bannos').bootstrapDualListbox({
      nonSelectedListLabel: 'No asignados',
      selectedListLabel: 'Asignados',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: true,
      nonSelectedFilter: '',
      infoText: 'Disponibles {0}',
      infoTextEmpty: 'Seleccionados',
      filterPlaceHolder: 'Buscar',
    });

  button_guardarasignacion.on('click', function(){
    button_guardarasignacion.html('<i class="fa fa-save"> </i> Guardar <i class="fa fa-cog fa-spin"></i>');
    
    $("#alerts").html('');

    $.ajax({
      url: form_asignarbannos.attr('action'),
      method: form_asignarbannos.attr('method'),
      data: form_asignarbannos.serialize(),
      dataType: 'json'
    }).success(function(json){
      if(json.result)
      {
        $("#alerts").html(msg('success', 'La información se ha guardado correctamente.'));
        button_guardarasignacion.html('<i class="fa fa-save"> </i> Guardar');
      }else{
        $("#alerts").html(msg('damage', 'Error al guardar la información.'));
        button_guardarasignacion.html('<i class="fa fa-save"> </i> Guardar');
      }
    });

    });
});