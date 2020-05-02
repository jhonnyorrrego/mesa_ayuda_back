//evento ejecutado en el adicionar
function add(data){
   cargarOpcionesClasificacion();
   autoClasificar();
}

//evento ejecutado en el editar
function edit(data){
    
}

//evento ejecutado en el mostrar
function show(data){
    let baseUrl = window.getBaseUrl();
}

//evento ejecutado anterior al adicionar
function beforeSendAdd(){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado posterior al adicionar
function afterSendAdd(xhr){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado anterior al editar
function beforeSendEdit(){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado posterior al editar
function afterSendEdit(xhr){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado anterior al devolver o rechazar
function beforeReject(){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado posterior al devolver o rechazar
function afterReject(xhr){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado anterior al confirmar o aprobar
function beforeConfirm(){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

//evento ejecutado posterior al confirmar o aprobar
function afterConfirm(xhr){
    return new Promise((resolve, reject) => {
      resolve();
    });
}

function cargarOpcionesClasificacion(){
	$.ajax({
		url : 'obtener_opciones_clasificacion.php',
		type : 'POST',
		dataType : 'json',
		success : function(respuesta){
			if(respuesta.success){
				$("#pre_clasificacion").append(respuesta.html);
			}
		}
	});
	
	$.post(
      'obtener_opciones_clasificacion.php',
      {
          token: localStorage.getItem('token'),
          key: localStorage.getItem('key')
      },
      function(response) {
          if (response.success) {
          		$("#pre_clasificacion").html(response.html);
          		$("#pre_clasificacion").select2('destroy');
          		$("#pre_clasificacion").select2();
          	
              /*top.notification({
                  type: "success",
                  message: response.message
              });
              top.closeTopModal();*/
          } else {
              /*top.notification({
                  type: "error",
                  message: response.message
              });*/
          }
      },
      "json"
  );
}
function autoClasificar(){
	$("#pre_clasificacion").change(function(){
		var x_valor = $(this).val();
		
		if(x_valor){
			$('input[name$="clasificacion"]').val(x_valor);
			$('input[name$="estado_ticket"]').val(2);
		} else {
			$('input[name$="clasificacion"]').val("");
			$('input[name$="estado_ticket"]').val(1);
		}
		
	});
}
