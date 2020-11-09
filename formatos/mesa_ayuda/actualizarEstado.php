<?php


use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\core\DatabaseConnection;
use Saia\controllers\JwtController;
use Saia\controllers\notificaciones\NotifierController;

$max_salida = 10;
$rootPath = $ruta = '';

while ($max_salida > 0) {
    if (is_file($ruta . 'index.php')) {
        $rootPath = $ruta;
        break;
    }

    $ruta .= '../';
    $max_salida--;
}

include_once $rootPath . 'app/vendor/autoload.php';

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
    $FtMesaAyuda -> estado_ticket = FtMesaAyuda::ESTADO_PENDIENTE;

    if ($FtMesaAyuda->save()) {
        $Response->notifications = NotifierController::prepare();
        
        $Response->data = $FtMesaAyuda -> getEstadoTicket();
    }
    ////////////////////// Cambia el estado a finalizado si se encuentra en ENTREGA o lo devuelve a por distribuir si se encontraba en recogida

    $Response->message = 'Estado asignado correctamente';
    $Response->success = 1;
} catch (Throwable $th) {
    $Response->message = $th->getMessage();
}

echo json_encode($Response);
