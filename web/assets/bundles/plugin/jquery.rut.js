var input_rut = $("#input_rut");
$(function(){
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
      	var url = input_rut.data('url');
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
        			return false;
        		}else{
        			$("#rut_invalido").html('');
        			return true;
        		}
        	}
        });

      }else{
        $("#rut_invalido").html('Rut inválido.');
        return false;
      }
  }
});