<?php

use Doctrine\DBAL\Types\Type;
use Saia\core\DatabaseConnection;
use Saia\MesaAyuda\controllers\MesaAyudaController;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\controllers\SessionController;
use Saia\models\vistas\VfuncionarioDc;

$max_salida = 6;
$rootPath = $ruta = "";

while ($max_salida > 0) {
    if (is_file($ruta . "sw.js")) {
        $rootPath = $ruta;
    }
    $ruta .= "../";
    $max_salida--;
}

include_once $rootPath . "app/vendor/autoload.php";

function mostrarPreClasificacionTicket($preclasificacion){
  $cadenaRetorno = '';
  
  $clasificacion = DatabaseConnection::getQueryBuilder()
        ->select('a.nombre','b.nombre as padre')
        ->from('ma_clasificacion','a')
        ->leftJoin('a','ma_clasificacion','b','a.cod_padre=b.idma_clasificacion')
        ->where('a.idma_clasificacion = :idma')
        ->setParameter(':idma',$preclasificacion, \Doctrine\DBAL\Types\Type::INTEGER)
        ->execute()->fetchAll();
        
  if($clasificacion[0]["padre"]){
    $cadenaRetorno .= $clasificacion[0]["padre"] . " - ";
  }
  $cadenaRetorno .= $clasificacion[0]["nombre"];
  return($cadenaRetorno);
}
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

function mostrarClasificacionTicket($clasificacion){  
  $cadenaRetorno = '';
  
  $datoClasificacion = DatabaseConnection::getQueryBuilder()
        ->select('a.nombre','b.nombre as padre')
        ->from('ma_clasificacion','a')
        ->leftJoin('a','ma_clasificacion','b','a.cod_padre=b.idma_clasificacion')
        ->where('a.idma_clasificacion = :idma')
        ->setParameter(':idma',$clasificacion, \Doctrine\DBAL\Types\Type::INTEGER)
        ->execute()->fetchAll();
  
  if($datoClasificacion[0]["padre"]){
    $cadenaRetorno .= $datoClasificacion[0]["padre"] . " - ";
  }
  $cadenaRetorno .= $datoClasificacion[0]["nombre"];
	
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
  $usuarioCodigo = SessionController::getValue('funcionario_codigo');
  if($usuarioCodigo == 1){
    $cadena = "";
  } else {
    $cadena = " and concat(',', d.responsables, ',') like '%," . $usuarioCodigo . ",%'";
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
?>