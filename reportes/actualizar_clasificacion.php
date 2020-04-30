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
include_once $rootPath . 'app/modules/back_mesa_ayuda/reportes/librerias.php';

$Response = (object) [
    'data' => new stdClass(),
    'message' => '',
    'success' => 0
];

try {
    JwtController::check($_REQUEST['token'], $_REQUEST['key']);

    if (!$_REQUEST['idft_mesa_ayuda']) {
        throw new Exception('Debe especificar el ticket', 1);
    }
    
    $FtMesaAyuda = new FtMesaAyuda($_REQUEST['idft_mesa_ayuda']);
    $FtMesaAyuda -> clasificacion = $_REQUEST['clasificacion'];
    $FtMesaAyuda -> estado_ticket = 2;//Clasificado

    if ($FtMesaAyuda->save()) {
        $Response->message = 'Clasificación asignada correctamente';
        $Response->notifications = NotifierController::prepare();
        $Response->success = 1;
    }
    ////////////////////// Cambia el estado a finalizado si se encuentra en ENTREGA o lo devuelve a por distribuir si se encontraba en recogida

    
    $Response->message = "El trámite se ha finalizado correctamente!";
    $Response->success = 1;
} catch (Throwable $th) {
    $Response->message = $th->getMessage();
}

echo json_encode($Response);
