// globales
var table_listausuarios     = $("#table_listausuarios");
var filtro_listaUsuario     = $("#filtro_listaUsuario");
var number_id               = $("#number_id");
var text_nombre             = $("#text_nombre");
var select_contacto         = $("#select_contacto");
var select_perfil           = $("#select_perfil");
var select_sucursal         = $("#select_sucursal");
var text_direccion          = $("#text_direccion");
var paginacion_listaUsuario = $("#paginacion_listaUsuario");

$(function(){

  $(".select2-single").select2();

  // funciones
  cargarLista(0);

  // filtros
  $("#filtro_listaUsuario input").keyup(function(){
    cargarLista(0);
  });

  $("#filtro_listaUsuario").on('change', 'select', function(){
    cargarLista(0);
  });

});

function cargarLista(pagina)
{
  // load
  $("#table_listausuarios tbody").html('<tr><td colspan="6" class="text-center"><i class="fa fa-cog fa-spin fa-3x fa-fw"></i></td></tr>');
  
  // variables
  var url = Routing.generate('usuario_ajax_cargarlista');

  // filtros
  var id        = number_id.val();
  var nombre    = text_nombre.val();
  var direccion = text_direccion.val();
  var perfil    = select_perfil.val();
  var sucursal  = select_sucursal.val();
  // var contacto  = select_contacto.val();

  $.ajax({
    url:url,
    data: {pagina:pagina, id:id, nombre:nombre, direccion:direccion, perfil:perfil, sucursal:sucursal},
    method: 'post',
    dataType: 'json'
  }).success(function(json){
    if(json.result)
    {
      $("#table_listausuarios tbody").html(json.lista);
      $("#paginacion_listaUsuario .btn-group").html(json.paginador);
    }else{
      $("#table_listausuarios tbody").html('<tr><td colspan="6">Sin información</td></tr>');
    }
    console.log(json);
  }).done(function(){
    // paginacion
    paginacion_listaUsuario.on('click','.btn_pagina', function(){
      console.log('aqui');
      var pag = $(this).data('pag');

      cargarLista(pag - 1);
    });

    // eliminar usuario
    $(".button_eliminarUsuario").on('click', function(){
      var id = $(this).data('id');

      $(this).html('<tr><td colspan="6" class="text-center"><i class="fa fa-cog fa-spin"></i></td></tr>');
      eliminarUsuario(id);
    });
    
  });
}

function eliminarUsuario(id)
{
  var url = Routing.generate('usuario_ajax_eliminar');
  $.ajax({
    url:url,
    data: {id:id},
    method: 'post',
    dataType: 'json'
  }).success(function(json){
    if(json.result)
    {
      cargarLista(0);
      console.log(json);
    }
  });
}