<?php


use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\core\DatabaseConnection;
use Saia\controllers\JwtController;
use Saia\controllers\notificaciones\NotifierController;

$max_salida = 10;
$rootPath = $ruta = '';

while ($max_salida > 0) {
    if (is_file($ruta . 'sw.js')) {
        $rootPath = $ruta;
        break;
    }

    $ruta .= '../';
    $max_salida--;
}

include_once $rootPath . 'app/vendor/autoload.php';

$opciones = '';
$Response = (object) [
    'data' => new stdClass(),
    'message' => '',
    'success' => 0
];

try {
    JwtController::check($_REQUEST['token'], $_REQUEST['key']);
    
    $clasificaciones = DatabaseConnection::getQueryBuilder()
        ->select('nombre', 'idma_clasificacion', 'cod_padre')
        ->from('ma_clasificacion')
        ->where('estado=1')
        ->andWhere('cod_padre=0')
        ->execute()->fetchAll();
    
    $opciones .= "<option value='' selected>Seleccione</option>";
    foreach($clasificaciones as $key){
      //$opciones .= "<option value='{$key["idma_clasificacion"]}'>{$key['nombre']}</option>";
      $opciones .= "<optgroup label='{$key['nombre']}' data-select2-id='{$key["idma_clasificacion"]}'>";
      $opciones .= generarSelectTicket($key["idma_clasificacion"]);
      $opciones .= "</optgroup>";
    }

    $Response->message = 'Clasificación asignada correctamente';
    $Response->notifications = NotifierController::prepare();
    $Response->success = 1;
    $Response->html = $opciones;
    ////////////////////// Cambia el estado a finalizado si se encuentra en ENTREGA o lo devuelve a por distribuir si se encontraba en recogida

    
    $Response->message = "El trámite se ha finalizado correctamente!";
    $Response->success = 1;
} catch (Throwable $th) {
    $Response->message = $th->getMessage();
}

function generarSelectTicket($idMaClasificacion){
  $opciones = '';
  $clasificaciones = DatabaseConnection::getQueryBuilder()
        ->select('nombre', 'idma_clasificacion', 'cod_padre')
        ->from('ma_clasificacion')
        ->where('cod_padre = :idma')
        ->setParameter(':idma', $idMaClasificacion, \Doctrine\DBAL\Types\Type::INTEGER)
        ->execute()->fetchAll();
  
  foreach($clasificaciones as $key){
    $opciones .= "<option value='{$key["idma_clasificacion"]}' data-idsql='{$key["idma_clasificacion"]}' data-nombre='{$key["nombre"]}' data-select2-id='{$key["idma_clasificacion"]}'>{$key['nombre']}</option>";
  }
  
  return($opciones);
}

echo json_encode($Response);
