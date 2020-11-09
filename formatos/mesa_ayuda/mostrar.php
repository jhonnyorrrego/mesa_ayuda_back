<?php
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

use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\controllers\SessionController;
use Saia\models\documento\Documento;

try {
    SessionController::goUp($_REQUEST['token'], $_REQUEST['key']);
    
    $documentId = $_REQUEST["documentId"];
    $Documento = new Documento($documentId);
    $FtMesaAyuda = $Documento->getFt();
    $Formato = $FtMesaAyuda->getFormat();

    $Documento->addRead(SessionController::getValue('idfuncionario'));     
    ?>


<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8"/>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=10.0, shrink-to-fit=no"/>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description"/>
    <meta content="" name="Cero K"/>
    <link href="<?= ABSOLUTE_SAIA_ROUTE ?>views/formato/css/mostrar.css" rel="stylesheet" type="text/css">
</head>

<body>
<?php if(
    !$_REQUEST['mostrar_pdf'] &&
    !$_REQUEST['actualizar_pdf'] &&
    0 == 0
): ?>

<div class="container bg-white mx-0 px-1 px-md-1 mw-100">
    <div id="documento" class="row p-0 m-0">
        <div id="pag-0" class="col-12 page_border bg-white">
            <div class="page_margin_top mb-0" id="doc_header">
                <?= Saia\controllers\functions\Header::crearEncabezadoPiePagina(
                    '',
                    $Documento
                ) ?>
            </div>
            <div id="pag_content-0" class="page_content">
                <div id="page_overflow">
                    <p><?= $FtMesaAyuda->mostrarInfoTicket() ?></p>
                </div>
            </div>
            <div class="page_margin_bottom" id="doc_footer">
                <?= Saia\controllers\functions\Header::crearEncabezadoPiePagina(
                    '',
                    $Documento
                ) ?>
            </div>
        </div> <!-- end page-n -->
    </div> <!-- end #documento-->
</div> <!-- end .container -->
<?php else:

    $params = [
        "type" => "TIPO_DOCUMENTO",
        "typeId" => $documentId,
    ];

    if(
        $_REQUEST["actualizar_pdf"] ||
        (
            !$Documento->pdf && (
                $Formato->mostrar_pdf == 1 ||
                $_REQUEST['mostrar_pdf']
            )
        )
    ){
        $params["actualizar_pdf"] = 1;
    }

    $url = ABSOLUTE_SAIA_ROUTE . "views/visor/pdfjs/viewer.php?";
    $url.= http_build_query($params);

    echo "<iframe width='100%' frameborder='0' onload='this.height = window.innerHeight - 20' src='{$url}'></iframe>";
endif;
    
$scope = FtMesaAyuda::SCOPE_ROUTE_PARAMS_SHOW;
$additionalParameters = $FtMesaAyuda->getRouteParams($scope);
$params = json_encode(array_merge($_REQUEST,$additionalParameters));
?>
    <script>
        $(function () {
            $.getScript("https://modulos.netsaia.com/saia_ma/saia_2019/app/modules/back_mesa_ayuda/formatos/mesa_ayuda/funciones.js", () => {
                window.routeParams = <?= $params ?>;
                show(<?= $params ?>)
            });
        });
    </script>
    </body>
</html>
<?php
} catch (\Throwable $th) {
    die($th->getMessage());
}