// globales
var form_usuario        = $("#form_usuario");
var check_verclave 	    = $("#check_verclave");
var input_clave         = $("#input_clave");
var input_rut           = $("#input_rut");
var button_guardar	    = $("#button_guardar");
var select_tipousuario  = $("#select_tipousuario");
var select_privilegios  = $("#select_privilegios");

$(function(){

  $(".select2-single").select2();

	// lista doble
	var demo1 = $('#select_privilegios').bootstrapDualListbox({
      nonSelectedListLabel: 'No asignados',
      selectedListLabel: 'Asignados',
      preserveSelectionOnMove: 'moved',
      moveOnSelect: true,
      nonSelectedFilter: '',
      infoText: 'Todos {0}',
      infoTextEmpty: 'Seleccionados',
      filterPlaceHolder: 'Buscar'
    });

    select_tipousuario.change(function(){
      if(select_tipousuario.val() == 1 )
      {
        $("#div_privilegios").addClass('hidden');
      }else{
        $("#div_privilegios").removeClass('hidden');
      }
    });

    // ver clave
   	check_verclave.on('click', function(){
   		if(check_verclave.prop('checked'))
  		{
  			input_clave.attr('type','text');
  		}else{
  			input_clave.attr('type','password');
  		}
   	});

    $.validator.addMethod("valueNotEquals", function(value, element, arg){
      return arg != value;
    }, "Value must not equal arg.");

    // validar formulario
    button_guardar.on('click', function(){
      form_usuario.validate({
          rules: {
              input_nombre:
              {
                required: true
              },
              input_apellido:
              {
                required: true
              },
              input_rut:
              {
                required: true
              },
              select_comuna:
              {
                valueNotEquals: "default"
              },
              input_direccion:
              {
                required: true
              },
              input_clave:
              {
                required: true
              }
          },
          messages: {
              input_nombre:
              {
                required: "Campo requerido"
              },
              input_apellido:
              {
                required: "Campo requerido"
              },
              input_rut:
              {
                required: "Campo requerido"
              },
              select_comuna:
              {
                valueNotEquals: "Seleccione una opción"
              },
              input_direccion:
              {
                required: "Campo requerido"
              },
              input_clave:
              {
                required: "Campo requerido"
              }
          },
          errorPlacement: function(error, element) {
              if (element.is(":radio") || element.is(":checkbox")) {
                  element.closest('.option-group').after(error);
              }
              else {
                  error.insertAfter(element);
              }
          }
      }).settings.ignore = ':not(select:hidden, input:visible, textarea:visible)';

      if(form_usuario.valid() && Fn.validaRut(input_rut.val()) && $("#input_correo").val() != '')
      {
        if($("#rut_invalido").data('valid') == 1)
        {
          form_usuario.submit();
          // console.log('valido');
        }else{
          $("html, body").animate({ scrollTop: 0 }, 600);
          $("#alert_error").removeClass('hidden');
        }
        
      }else{
        $("html, body").animate({ scrollTop: 0 }, 600);
        $("#alert_error").removeClass('hidden');
      }
    });

    // validar rut
    input_rut.focusout(function(){
      validarRut();
    });

    var Fn = {
      // Valida el rut con su cadena completa "XXXXXXXX-X"
      validaRut : function (rutCompleto) {
          if (!/^[0-9]+-[0-9kK]{1}$/.test( rutCompleto ))
              return false;
          var tmp     = rutCompleto.split('-');
          var digv    = tmp[1]; 
          var rut     = tmp[0];
          if ( digv == 'K' ) digv = 'k' ;
          return (Fn.dv(rut) == digv );
      },
      dv : function(T){
          var M=0,S=1;
          for(;T;T=Math.floor(T/10))
              S=(S+T%10*(9-M++%6))%11;
          return S?S-1:'k';
      }
  }

  var validarRut = function()
  {
    if(Fn.validaRut(input_rut.val()))
      {
        var url = Routing.generate('plugin_validar_rut');
        var rut = input_rut.val();

        $.ajax({
          url: url,
          data: {rut:rut},
          dataType: 'json'
        }).success(function(json){
          if(json.result)
          {
            if(json.rut){
              $("#rut_invalido").html('El rut ingresado ya está registrado.');
              $("#rut_invalido").data('valid', 0);
            }else{
              $("#rut_invalido").html('');
              $("#rut_invalido").data('valid', 1);
            }
          }
        });

      }else{
        $("#rut_invalido").html('Rut inválido.');
        $("#rut_invalido").data('valid', 0);
      }
  }

});