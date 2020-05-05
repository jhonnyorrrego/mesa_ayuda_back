<?php
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

use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\controllers\SessionController;
use Saia\models\documento\Documento;

try {
    SessionController::goUp($_REQUEST['token'], $_REQUEST['key']);
    
    $documentId = $_REQUEST["documentId"];
    $Documento = new Documento($documentId);
    $FtMesaAyuda = $Documento->getFt();
    $Formato = $FtMesaAyuda->getFormat();
    $view = $_REQUEST['view'] ?? 'html';

    if(
        !$_REQUEST['mostrar_pdf'] && !$_REQUEST['actualizar_pdf'] && 
        ($view == 'pdf' || 0 == 0)
    ): 
        $Documento->addRead($documentId);                                  
    ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=10.0, shrink-to-fit=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-touch-fullscreen" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta content="" name="description" />
    <meta content="" name="Cero K" />

    <?php if ($view == 'html') : ?>
    <link href="<?= ABSOLUTE_SAIA_ROUTE ?>views/formato/css/mostrar.css" rel="stylesheet" type="text/css">
    <?php endif; ?>
</head>

<body>
    <div class="container bg-master-lightest mx-0 px-1 px-md-1 mw-100">
        <div id="documento" class="row p-0 m-0">
            <div id="pag-0" class="col-12 page_border bg-white">
                <?php if ($view == 'html') : ?>
                <div class="page_margin_top mb-0" id="doc_header">
                    <?= Saia\controllers\functions\Header::crearEncabezadoPiePagina(
                                        '<table align="center" border="1" cellspacing="0" style="border-collapse:collapse; width:100%">
              <tbody>
                  <tr>
                      <td style="border-color:#b6b8b7; text-align:center; width:30%">{*nombre_empresa*}</td>
                      <td style="border-color:#b6b8b7; text-align:center; vertical-align:middle; width:40%"><strong>{*nombre_formato*}</strong></td>
                      <td style="border-color:#b6b8b7; text-align:center; vertical-align:middle; width:30%">{*logo_empresa*}</td>
                  </tr>
              </tbody>
          </table>
          ',
                                        $Documento
                                    ) ?>
                </div>
                <?php endif; ?>
                <div id="pag_content-0" class="page_content">
                    <div id="page_overflow">
                        <p style="text-align:right"><?= Saia\controllers\functions\CoreFunctions::mostrar_qr($FtMesaAyuda) ?></p>
<table border="1" cellpadding="1" cellspacing="1" class="table table-bordered" style="width:100%">
  <tbody>
    <tr>
      <td colspan="2" style="text-align:center"><strong>Descripci&oacute;n del ticket</strong></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:justify"><?= Saia\controllers\generator\component\ComponentBuilder::callShowValue('descripcion',$FtMesaAyuda) ?></td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Fecha de solicitud</strong></td>
      <td style="width:70%">&nbsp;<?= Saia\controllers\functions\CoreFunctions::fecha_aprobacion($FtMesaAyuda) ?></td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Solicitante</strong></td>
      <td style="width:70%">&nbsp;<?= Saia\controllers\functions\CoreFunctions::creador_documento($FtMesaAyuda) ?></td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Anexos digitales</strong></td>
      <td style="width:70%">&nbsp;<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue('anexos',$FtMesaAyuda) ?></td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Clasificaci&oacute;n por usuario</strong></td>
      <td style="width:70%">&nbsp;<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue('pre_clasificacion',$FtMesaAyuda) ?></td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Estado</strong></td>
      <td style="width:70%">&nbsp;<?= $FtMesaAyuda->getEstadoTicket() ?></td>
    </tr>
  </tbody>
</table>
<p><?= Saia\controllers\functions\CoreFunctions::mostrar_estado_proceso($FtMesaAyuda) ?></p>                    </div>
                </div>
                <?php if ($view == 'html') : ?>
                <div class="page_margin_bottom" id="doc_footer">
                    <?= Saia\controllers\functions\Header::crearEncabezadoPiePagina(
                                            '',
                                            $Documento
                                    ) ?>
                </div>
                <?php endif; ?>
            </div> <!-- end page-n -->
        </div> <!-- end #documento-->
    </div> <!-- end .container -->
</body>
            <?php
                $scope = FtMesaAyuda::SCOPE_ROUTE_PARAMS_SHOW;
                $additionalParameters = $FtMesaAyuda->getRouteParams($scope);
                $params = json_encode(array_merge($_REQUEST,$additionalParameters));
            ?><script>
    $(function() {
        let file = "https://modulos.netsaia.com/saia_ma/saia_2019/app/modules/back_mesa_ayuda/formatos/mesa_ayuda/funciones.js";
        $.getScript(file, () => {
            window.routeParams = <?= $params ?>;
            show(<?= $params ?>)
        });
    });
</script>

</html>

    <?php else: ?>
    <script>
        $(function(){
            $.getScript("https://modulos.netsaia.com/saia_ma/saia_2019/app/modules/back_mesa_ayuda/formatos/mesa_ayuda/funciones.js");
        });
    </script>
<?php        
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
} catch (\Throwable $th) {
    die($th->getMessage());
}