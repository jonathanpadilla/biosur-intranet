$(function(){
  var form      = $("#form_wizard_venta");
  var crear     = form.data('crear');


    $('#myModal').on('shown.bs.modal', function (event) {
        $("#plat").html("");
        $("#plon").html("");

        var button = $(event.relatedTarget);
        var id = button.data('id');
        var nlat = '-37.468949';
        var nlon = '-72.3521878';
        var lat;
        var lon;

        if($("#tlat"+button.data('id')).val() != 0 || $("#tlon"+button.data('id')).val() != 0)
        {
          nlat = $("#tlat"+button.data('id')).val();
          nlon = $("#tlon"+button.data('id')).val();
        }

        var map = new GMaps({
          el: '#map',
          lat:nlat,
          lng:nlon,
          zoom: 14,
          click: function(e) {
            var lat = e.latLng.lat();
            var lon = e.latLng.lng();

            map.removeMarkers();
            map.addMarker({
              lat: lat,
              lng: lon
            });

            $("#plat").html(lat);
            $("#plon").html(lon);

            // span
            $("#slat"+button.data('id')).html(lat);
            $("#slon"+button.data('id')).html(lon);

            // inputs
            $("#tlat"+button.data('id')).val(lat);
            $("#tlon"+button.data('id')).val(lon);
          }
        });

        if($("#tlat"+button.data('id')).val() != 0 || $("#tlon"+button.data('id')).val() != 0)
        {
          map.removeMarkers();
          map.addMarker({
              lat: nlat,
              lng: nlon
            });
        }

        if($("#select_comuna_"+id+" option:selected").text() != '' &&  $("#tlat"+button.data('id')).val() == 0)
        {
          GMaps.geocode({
            address: $("#select_comuna_"+id+" option:selected").text()+", chile",
            callback: function(results, status) {
              if (status == 'OK') {
                var latlng = results[0].geometry.location;
                map.setCenter(latlng.lat(), latlng.lng());
                map.addMarker({
                  lat: latlng.lat(),
                  lng: latlng.lng()
                });
              }
            }
          });
        }

    });

  // Form Wizard 
  form.validate({
    ignore: [], 
    rules: {
      input_nombre:
      {
        required: true
      },
      input_rut:
      {
        required: true
      },
      input_direccion:
      {
        required: true
      }
    },
    messages: {
      input_nombre:
      {
        required: "Campo requerido"
      },
      input_rut:
      {
        required: "Campo requerido"
      },
      input_direccion:
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

  });

  form.children(".wizard").steps({
    headerTag: ".wizard-section-title",
    bodyTag: ".wizard-section",
    onStepChanging: function(event, currentIndex, newIndex) {
      form.validate().settings.ignore = ":disabled,:hidden";

      if(form.valid() && Fn.validaRut($("#input_rut").val()))
      {
        $("#table_listadetalle").html('<tr><td colspan="5" class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></td></tr>');
        // console.log(form.serialize());
        var url = Routing.generate('venta_ajax_crearventa');
        $.ajax({
          url: url,
          data: form.serialize(),
          dataType: 'json',
          method: 'post'
        }).success(function(json){
          // console.log(json);
          if(json.result)
          {
            // llenar informacion final
            $("#dd_nombrecliente").html(json.info_venta.nombre_cliente);
            $("#dd_rutcliente").html(json.info_venta.rut_cliente);
            $("#dd_girocliente").html(json.info_venta.giro_cliente);
            $("#dd_comunacliente").html(json.info_venta.nombre_comuna);
            $("#dd_provinciacliente").html(json.info_venta.nombre_provincia);
            $("#dd_regioncliente").html(json.info_venta.nombre_region);
            $("#dd_direccioncliente").html(json.info_venta.direccion_cliente);
            $("#dd_comentariocliente").html(json.info_venta.comentario);

            $("#info_comentarioarriendo").html((json.info_venta.comentario_detalle)?'<strong>Comentario:</strong><br>'+json.info_venta.comentario_detalle:'');

            // contacto cliente
            var comntacto_cliente ='';
            var tipo_contacto = '';
            var nombre_contacto = '';

            if(json.info_venta.contacto)
            {
              $.each(json.info_venta.contacto, function(index, value){
                switch(value[1]) {
                    case '1':
                        tipo_contacto = 'Telefono';
                        break;
                    case '2':
                        tipo_contacto = 'Celular';
                        break;
                    case '3':
                        tipo_contacto = 'Correo';
                        break;
                    default:
                        tipo_contacto = 'Otro contácto';
                }

                nombre_contacto = (value[3] != '')?'('+value[3]+')':'';

                comntacto_cliente = comntacto_cliente+'<dt>'+tipo_contacto+'</dt>';
                comntacto_cliente = comntacto_cliente+'<dd>'+value[2]+' '+nombre_contacto+'</dd>';
                // console.log(value[2]);
              });
            }
            $("#direccion_cliente").html(comntacto_cliente);

            // detalle cliente
            var fila_detalle = '';
            var total_final = 0;
            $.each(json.info_venta.detalle, function(index, value){
              if(value['cantidadbano'] != '' || value['cantidadcaseta'] != '' || value['cantidadducha'] != '' || value['cantidadexterno'] != '' || value['cantidadlavamano'] != '' )
              {
                var cant_bano       = ((value['cantidadbano'] != '')? '<li>Baños: '+value['cantidadbano']+' - $'+value['netobano']+'</li>':'');
                var cant_caseta     = ((value['cantidadcaseta'] != '')? '<li>Casetas: '+ value['cantidadcaseta']+' - $'+value['netocaseta']+'</li>':'');
                var cant_ducha      = ((value['cantidadducha'] != '')? '<li>Duchas: '+ value['cantidadducha']+' - $'+value['netoducha']+'</li>':'');
                var cant_externos   = ((value['cantidadexterno'] != '')? '<li>Baños externos: '+ value['cantidadexterno']+' - $'+value['netoexterno']+'</li>':'');
                var cant_lavamanos  = ((value['cantidadlavamano'] != '')? '<li>Lavamanos: '+ value['cantidadlavamano']+'</li>':'');

                var text_cant = cant_bano+cant_caseta+cant_ducha+cant_externos+cant_lavamanos;
                var total     = parseInt((value['netobano'] || 0)) + parseInt((value['netocaseta'] || 0)) + parseInt((value['netoducha'] || 0)) + parseInt((value['netoexterno'] || 0));

                fila_detalle = fila_detalle + '<tr><td>Arriendo baño químico</td>';
                fila_detalle = fila_detalle + '<td><ul>'+text_cant+'</ul></td>';
                fila_detalle = fila_detalle + '<td>'+value['dias']+'</td>';
                fila_detalle = fila_detalle + '<td>'+value['direccion']+', '+value['nombre_comuna']+', '+value['nombre_provincia']+', '+value['nombre_region']+'</td>';
                fila_detalle = fila_detalle + '<td>'+total+'</td></tr>';

                total_final = parseInt(total_final) + parseInt(total);
              }
            });

            $("#into_totaldetalle").html(total_final);

            if(fila_detalle)
            {
              $("#table_listadetalle").html(fila_detalle);
            }else{
              $("#table_listadetalle").html('<tr><td colspan="5">Sin información</td></tr>');
            }

          }else{
            $("#table_listadetalle").html('<tr><td colspan="5">Sin información</td></tr>');
          }
        });
        return form.valid();
      }
      
    },
    onFinishing: function(event, currentIndex) {
      form.validate().settings.ignore = ":disabled";
      return form.valid();
    },
    onFinished: function(event, currentIndex) {
      
      // validar campos
      if($("#rut_invalido").data('valid') == 1)
      {
        // $(".actions ul li").last().html('<a href="#finish" role="menuitem">Finalizar <i class="fa fa-cog fa-spin"></i></a>');
        $.ajax({
          url: $("#form_wizard_venta").attr('action'),
          dataType: 'json',
          data: form.serialize(),
          method: 'post',
        }).success(function(json){
          if(json.result)
          {
            window.location.href = Routing.generate('venta_vista_verventa', {id:json.idventa}, true);
          }else{
            $("#alert_error").removeClass('hidden');
            // $(".actions ul li").last().html('<a href="#finish" role="menuitem">Finalizar</a>');
          }
          
        });
      }else{
        console.log('faltan datos');
        return false;
      }
        
    },
    labels: {
      cancel: "Cancelar",
      pagination: "Paginación",
      finish: "Finalizar",
      next: "Siguiente",
      previous: "Anterior",
      loading: "Cargando ..."
    }
  });

  $("#daterangepicker1").datepicker({
    prevText: '<i class="fa fa-chevron-left"></i>',
    nextText: '<i class="fa fa-chevron-right"></i>',
    showButtonPanel: false,
    beforeShow: function(input, inst) {
      var newclass = 'admin-form';
      var themeClass = $(this).parents('.admin-form').attr('class');
      var smartpikr = inst.dpDiv.parent();
      if (!smartpikr.hasClass(themeClass)) {
        inst.dpDiv.wrap('<div class="' + themeClass + '"></div>');
      }
    }
  });

  $(".select2-single").select2({ width: '100%' });

  cargarFormularioCliente();

  $("#select_cliente").change(function(){
      cargarFormularioCliente();
    });

  // validar rut
  $("#input_rut").focusout(function(){
    // console.log('focus');
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
    if(Fn.validaRut($("#input_rut").val()))
      {
        var url = Routing.generate('cliente_ajax_validarrutcliente');
        var rut = $("#input_rut").val();

        // console.log('buscar id');

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

  // mostrar tabs
  $("a.tab").on('click', function(){
    var tab     = $(this);
    var idtab   = tab.data('tab');
    var nextid  = idtab + 1;

    $("#tab_" + idtab).removeClass('hidden');
    $(".tab_" + nextid).removeClass('hidden');

    // console.log(idtab)
  });

  // limpiar coordenadas
  $(".btn-limpiarmapa").on('click', function(){
    var btn = $(this);
    var id = btn.data('id');


    // span
    $("#slat"+id).html('No seleccionado');
    $("#slon"+id).html('No seleccionado');

    // inputs
    $("#tlat"+id).val(0);
    $("#tlon"+id).val(0);
  });
});

  function cargarFormularioCliente()
  {
    if($("#select_cliente").val() == 'nuevo' )
    {
      $("#input_rut").val('');
      $("#input_nombre").val('');
      $("#input_giro").val('');
      $("#input_direccion").val('');
      $("#textarea_comentario").val('');
      $("#select_comuna option[value='1']").prop('selected', 'selected').change();

      $("#input_rut").prop('disabled', false);
      $("#filas_contactos").html('');
      $("#rut_invalido").data('valid', 0);

    }else{
      // $("#input_rut").addClass('hidden');
      $("#input_rut").attr('readonly', true);
      $("#rut_invalido").data('valid', 1);
      $("#rut_invalido").html('');

      $.ajax({
        url: Routing.generate('cliente_ajax_clienteporid'),
        data: {id: $("#select_cliente").val()},
        dataType: 'json'
      }).success(function(json){
        if(json.result)
        {
          $("#input_rut").val(json.datosCliente.rut);
          $("#input_nombre").val(json.datosCliente.nombre);
          $("#input_giro").val(json.datosCliente.giro);
          $("#input_direccion").val(json.datosCliente.direccion);
          $("#textarea_comentario").val(json.datosCliente.comentario);

          $("#select_comuna option[value='"+json.datosCliente.comuna+"']").prop('selected', 'selected').change();
          $("#filas_contactos").html(json.datosContacto);
        }
        console.log(json);
      });
      // $("#input_rut").val('17913418-7');
      // $("#input_nombre").val($("#select_cliente").val());
    }
    
  }