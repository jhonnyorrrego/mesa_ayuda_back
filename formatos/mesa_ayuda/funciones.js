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
    eventoGuardarComentario();
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
	/*$.ajax({
		url : 'obtener_opciones_clasificacion.php',
		type : 'POST',
		dataType : 'json',
		success : function(respuesta){
			if(respuesta.success){
				$("#pre_clasificacion").append(respuesta.html);
			}
		}
	});*/
	
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
		} else {
			$('input[name$="clasificacion"]').val("");
		}
		
	});
}

function eventoGuardarComentario(){
		$("#save_document").click(function(){
			
				var confirmacionComentario = top.confirm({
            id: 'question',
            type: 'success',
            title: 'Guardando!',
            message: 'Esta seguro de guardar este comentario?',
            position: 'center',
            timeout: 0,
            buttons: [
                [
                    '<button><b>Si</b></button>',
                    function (instance, toast) {
                    		
                    		envioGuardarComentario();
                    		
                        instance.hide(
                            {transitionOut: 'fadeOut'},
                            toast,
                            'button'
                        );
                    },
                    true
                ],
                [
                    '<button>NO</button>',
                    function (instance, toast) {
                        instance.hide(
                            {transitionOut: 'fadeOut'},
                            toast,
                            'button'
                        );
                    }
                ]
            ]
        });
    });
}

function envioGuardarComentario(){		
		var Comentario = new Object();
		Comentario.comment = $("#descripcion").val();
		if(!Comentario.comment){
				top.notification({
            message: 'Escribe un comentario',
            type: 'error',
            title: 'Error!'
        });
        
        return false;
		}
		
		var iddoc = $("#iddoc").val();
		
    $.ajax({
        url: `../../app/comentarios/guardar.php`,
        dataType: 'json',
        type: 'POST',
        async: false,
        data: {
            token: localStorage.getItem('token'),
            key: localStorage.getItem('key'),
            relation: iddoc,
            comment: Comentario
        },
        success: function(response) {
            if (response.success) {
            		top.notification({
                    message: 'Comentario registrado',
                    type: 'success',
                    title: ''
                });
                data = true;
                location.reload();
                
            } else {
                top.notification({
                    message: response.message,
                    type: 'error',
                    title: 'Error!'
                });
            }
        }
    });

    return data;
}
