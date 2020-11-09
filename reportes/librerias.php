<?php

use Doctrine\DBAL\Types\Type;
use Saia\core\DatabaseConnection;
use Saia\MesaAyuda\controllers\MesaAyudaController;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\controllers\SessionController;
use Saia\models\vistas\VfuncionarioDc;
use Saia\controllers\DateController;
use Saia\models\documento\ComentarioDocumento;

$max_salida = 6;
$rootPath = $ruta = "";

while ($max_salida > 0) {
    if (is_file($ruta . "index.php")) {
        $rootPath = $ruta;
        break;
    }
    $ruta .= "../";
    $max_salida--;
}

include_once $rootPath . "app/vendor/autoload.php";

function mostrarUsuarioTicket($dependencia){
  $cadenaRetorno = '';
  
  $Usuario = VfuncionarioDc::findByRole($dependencia);
  $cadenaRetorno = $Usuario -> getName();
  
  return($cadenaRetorno);
}
function listarClasificacionesTicket($id = null, $iddocumento = null)
{
  $opciones = '';
  $cadenaRetorno = '';
  
  $clasificaciones = DatabaseConnection::getQueryBuilder()
        ->select('nombre', 'idma_clasificacion', 'cod_padre')
        ->from('ma_clasificacion')
        ->where('estado=1')
        ->andWhere('cod_padre=0')
        ->execute()->fetchAll();
  foreach($clasificaciones as $key){
    //$opciones .= "<option value='{$key["idma_clasificacion"]}'>{$key['nombre']}</option>";
    $opciones .= "<optgroup label='{$key['nombre']}'>";
    $opciones .= generarSelectTicket($key["idma_clasificacion"]);
    $opciones .= "</optgroup>";
  }
  
  $cadenaRetorno = '<select style="width:150px" class="clasificacion" id="clasificacion' . $iddocumento . '" idft_mesa_ayuda="' . $id . '"><option value="">Seleccione</option>' . $opciones . '</select>
  <script>
  $("#clasificacion' . $iddocumento . '").select2();
  </script>';
  
  return($cadenaRetorno);
}

function generarSelectTicket($idMaClasificacion){
  $clasificaciones = DatabaseConnection::getQueryBuilder()
        ->select('nombre', 'idma_clasificacion', 'cod_padre')
        ->from('ma_clasificacion')
        ->where('cod_padre = :idma')
        ->setParameter(':idma', $idMaClasificacion, \Doctrine\DBAL\Types\Type::INTEGER)
        ->execute()->fetchAll();
  
  foreach($clasificaciones as $key){
    $opciones .= "<option value='{$key["idma_clasificacion"]}'>{$key['nombre']}</option>";
  }
  
  return($opciones);
}

function mostrarClasificacionTicket($idFtMesaAyuda){
  global $FtMesaAyuda;
    
  $cadenaRetorno = '';
  $FtMesaAyuda = new FtMesaAyuda($idFtMesaAyuda);
  $cadenaRetorno = $FtMesaAyuda -> getClasificacion();

	return($cadenaRetorno);
}

function mostrarEstadoTicket($idFtMesaAyuda,$estado_ticket=''){
    global $FtMesaAyuda;
    if(!$FtMesaAyuda){
        $FtMesaAyuda = new FtMesaAyuda($idFtMesaAyuda);
    }
    $cadenaRetorno = $FtMesaAyuda -> getEstadoTicket(1);
    
    return($cadenaRetorno);
}

function verRastroTicket($iddocumento){
	$cadenaRetorno = '';
	
	$cadenaRetorno .= '<span class="px-1 cursor fa fa-road f-20" id="show_history" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="Trazabilidad"></span>';
	
	$cadenaRetorno .= "<script>
	$(document).off('click', '#show_history');
    $(document).on('click', '#show_history', function() {
        let route = `${baseUrl}views/documento/linea_tiempo.php`;
        $('#history_content').load(route, {
            documentId: params.documentId
        });
        showTab('#historytab_accordion');
    });
    </script>";
	
	return($cadenaRetorno);
}

function verDocumentoTicket($iddocumento,$numero){
	$cadenaRetorno = '';
	
	$cadenaRetorno .= '<div class="kenlace_saia" enlace="views/documento/index_acordeon.php?documentId=' . $iddocumento . '" conector="iframe" titulo="No Registro ' . $numero . '"><center><button class="btn btn-complete">' . $numero . '</button></center></div>';
	
	return($cadenaRetorno);
}

function filtrarResponsableTicket(){
  $cadena = "";
  $estadoPendiente = FtMesaAyuda::ESTADO_PENDIENTE;
  
  $usuarioCodigo = SessionController::getValue('funcionario_codigo');
  if($usuarioCodigo == 1){
    $cadena .= "";
  } else {
    $cadena .= " and concat(',', d.responsables, ',') like '%," . $usuarioCodigo . ",%'";
  }
  
  return($cadena);
}

function accionesTicket($iddocumento){
  $cadenaRetorno = '<a class="crear_tarea cursor" iddocumento="' . $iddocumento . '" style=""><i class="fa fa-calendar" style="vertical-align: middle;" title="Asignar tarea"></i></a>';
  
  return($cadenaRetorno);
}

function contadorTareaTicket($iddocumento){
  $cadenaRetorno = '';
  
  $totalTareas = DatabaseConnection::getQueryBuilder()
    ->select('count(*) as cantidad')
    ->from('documento_tarea','a')
    ->where('a.fk_documento = :iddocumento')
    ->andWhere('a.estado=1')
    ->setParameter(':iddocumento',$iddocumento, Type::getType('integer'))
    ->execute()->fetchAll();
  
  $totalFinalizados = DatabaseConnection::getQueryBuilder()
    ->select('count(*) as cantidad')
    ->from('documento_tarea','a')
    ->join('a','tarea_estado','b','a.fk_tarea=b.fk_tarea')
    ->where('a.fk_documento = :iddocumento')
    ->andWhere('b.estado=1')
    ->andWhere('b.valor=1')
    ->setParameter(':iddocumento',$iddocumento, Type::getType('integer'))
    ->execute()->fetchAll();
  
  $cadenaRetorno = $totalFinalizados[0]["cantidad"] . "/" . $totalTareas[0]['cantidad'];
  
  return($cadenaRetorno);
}

function vencimientoTicket($fecha,$tipo_dias,$cant_dias,$estado_ticket){
  $cadenaRetorno = '';
  $fechaFinal = ''; //fecha final calculada
  $hoy = new DateTime(date('Y-m-d'));
  if($cant_dias == 'cant_dias' || $cant_dias == ''){
    $cant_dias = 5;
  }
  
  if($tipo_dias == 1 || $tipo_dias == 'tipo_dias'){//sumo dias corridos a la fecha de creacion
    $fechaObject = new DateTime($fecha);
    $fechaObject->add(new DateInterval("P{$cant_dias}D"));
    $fechaFinal = $fechaObject->format('Y-m-d');
    $fechaFinalObject = new DateTime($fechaFinal);
  } else if($tipo_dias == 2){//sumo dias habiles a la fecha de creacion
    $fechaObject = new DateTime($fecha);
    $fechaFinalObject = DateController::addBusinessDays($fechaObject,$cant_dias);
    $fechaFinal = $fechaFinalObject->format('Y-m-d');
    //$fechaFinalObject = new DateTime($fechaFinal);
  } else {
    $cadenaRetorno = "<span class='badge badge-danger'>Sin definir</span>";
    return($cadenaRetorno);
  }
  
  $interval = $hoy->diff($fechaFinalObject);//Resultado de dias al restar la fecha de hoy con la fecha final del ticket (Objeto)
  //$diasResta = $interval->format('%a');//Obtengo la cantidad de dias
  $diasResta = ($interval->invert == 1) ? ' - ' . $interval->days  : $interval->days;
  
  
  if($estado_ticket == FtMesaAyuda::ESTADO_TERMINADO){//Si el ticket esta terminado
    $color = 'badge badge-success';//verde
  } else {
    if($diasResta > 3){//Si los dias son mayor a 4
      $color = 'badge badge-secondary';//Gris
    } else if($diasResta <= 3 && $diasResta >= 1){//Si los dias son 
      $color = 'badge badge-warning';//Amarillo
    } else if($diasResta < 1){
      $color = 'badge badge-danger';//Rojo
    }
  }
  
  $cadenaRetorno = "<span class='{$color}'>{$fechaFinal}</span>";
  
  return($cadenaRetorno);
}

function optionsTickets($idFtMesaAyuda,$iddocumento){
    return '
        <div class="dropdown" id="opciones_ticket' . $iddocumento . '">
            <button class="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-left bg-white" role="menu">
                <a href="#" class="dropdown-item crear_tarea" iddocumento="' . $iddocumento. '">
                    <i class="fa fa-calendar"></i> Asignar tarea
                </a>
                <a href="#" class="dropdown-item reclasificar" iddocumento="' . $iddocumento. '">
                    <i class="fa fa-edit"></i> Reclasificar
                </a>
                <a href="#" class="dropdown-item show_task" iddocumento="' . $iddocumento. '">
                    <i class="fa fa-eye"></i> Ver tareas
                </a>
            </div>
        </div>
        <br />
        <div style="display:none" class="capaReclasifica' . $iddocumento . '">
            ' . listarClasificacionesTicket($idFtMesaAyuda,$iddocumento) . '
        </div>';
}

function getDescripcionTicket($id,$iddocumento,$numero,$descripcion){
    $comments = ComentarioDocumento::findAllByAttributes([
        'fk_documento' => $iddocumento
    ]);
    $comments = array_reverse($comments);
    $comentarios = parsearComentarioTicket($comments);
  
    $html = '';
    
    $html .= '<div class="dropdown dropdown2">';
    $html .= '<div class="" idticket="' . $id . '" dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' . $descripcion . '</div>';
    $html .= "<div class='dropdown-menu border border-dark rounded w-100' id='title_" . $id . "' style='z-index: 1000;'>
                <div class='container px-3 py-3'>
                  <div class='border-bottom'>
                    <div>" . mostrarEstadoTicket($id) . ' <span class="text-muted">Ticket # ' . $numero . "</span>
                    </div>
                    <div class='pt-3'>" . $descripcion . "
                    </div>
                    <div class='pt-3 text-muted'>Último comentario
                    </div>
                  </div>
                  <div class='pt-3'>
                  " . $comentarios . "
                  </div>
                </div>
             </div>";
    $html .= '</div>';
    $html .= '';
    
    return $html;
}

function parsearComentarioTicket($comments){
    $html = '';
    foreach ($comments as $ComentarioDocumento) {      
        $html .= "<div class='h5'>" . $ComentarioDocumento->Funcionario->getName() . "</div>" . $ComentarioDocumento->comentario . "<span class='float-right hint-text'>" . DateController::convertDate($ComentarioDocumento->fecha) . "</span>";
        break;
    }
    
    return $html;
}
?>