<?php
$max_salida = 10;
$rootPath = $ruta = "";

while ($max_salida > 0) {
    if (is_file($ruta . "sw.js")) {
        $rootPath = $ruta;
    }

    $ruta .= "../";
    $max_salida --;
}

include_once $rootPath . 'app/vendor/autoload.php';
include_once $rootPath . 'views/assets/librerias.php';

use Saia\controllers\SessionController;
use Saia\controllers\generator\component\ComponentBuilder;
use Saia\controllers\AccionController;
use Saia\models\formatos\Formato;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;

SessionController::goUp($_REQUEST['token'], $_REQUEST['key']);

$Formato = new Formato(54);
$documentId = $_REQUEST['documentId'] ?? 0;
    $FtMesaAyuda = new FtMesaAyuda;?><!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>SGDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=10.0, shrink-to-fit=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">

            <?= jquery() ?>        <?= bootstrap() ?>        <?= cssTheme() ?>    </head>

<body>

                <div class='container-fluid container-fixed-lg col-lg-8' style="overflow: auto;height:100vh">
                        <div class='card card-default'>
                <div class='card-body'>
                    <h5 class='text-black w-100 text-center'>
                        Mesa de ayuda                    </h5>
                    <form name='formulario_formatos' id='formulario_formatos' role='form' autocomplete='off'>
                        <input type='hidden' name='documento_iddocumento' value=''>
<input type='hidden' name='encabezado' value='1'>
<input type='hidden' name='firma' value='1'>
<input type='hidden' name='idft_mesa_ayuda' value=''>

        <?php
        $selected = $FtMesaAyuda->dependencia ?? '';
        $query = Saia\core\DatabaseConnection::getQueryBuilder();
        $roles = $query
            ->select("dependencia as nombre, iddependencia_cargo, cargo")
            ->from("vfuncionario_dc")
            ->where("estado_dc = 1 and login = :login")
            ->andWhere(
                $query->expr()->lte('fecha_inicial', ':initialDate'),
                $query->expr()->gte('fecha_final', ':finalDate')
            )->setParameter(":login", Saia\controllers\SessionController::getLogin())
            ->setParameter(':initialDate', new DateTime(), \Doctrine\DBAL\Types\Type::getType('datetime'))
            ->setParameter(':finalDate', new DateTime(), \Doctrine\DBAL\Types\Type::getType('datetime'))
            ->execute()->fetchAll();
    
        $total = count($roles);

        if ($total > 1) {

            echo "<div class='form-group form-group-default form-group-default-select2 required' id='group_dependencie'>
            <label>PERTENECE A</label>
            <select class='full-width select2-hidden-accessible' name='dependencia' id='dependencia' required>";
            foreach ($roles as $row) {
                echo "<option value='{$row["iddependencia_cargo"]}'>
                    {$row["nombre"]} - ({$row["cargo"]})
                </option>";
            }
    
            echo "</select>
                <script>
                $(function (){
                    $('#dependencia').select2();
                    $('#dependencia').val({$selected});
                    $('#dependencia').trigger('change');
                });  
                </script>
                </div>";
        } else if ($total == 1) {
            echo "<input class='required' type='hidden' value='{$roles[0]['iddependencia_cargo']}' id='dependencia' name='dependencia'>";
        } else {
            throw new Exception("Error al buscar la dependencia", 1);
        }
        ?>

        <div class='form-group form-group-default form-group-default-select2 required' id='group_pre_clasificacion'>
            <label title=''>CLASIFICACIóN</label>
            <select class='full-width' name='pre_clasificacion' id='pre_clasificacion' required>
            <option value=''>Por favor seleccione...</option>
        <option value='121' data-key='1'>
                 
            </option></select>
                <script>
                $(document).ready(function() {
                    $('#pre_clasificacion').select2();
                });
                </script>
        </div>
            <div class="form-group form-group-default required" id="group_descripcion">
                <label title="">
                    DESCRIPCIóN
                </label>
                <textarea 
                    name="descripcion"
                    id="descripcion" 
                    rows="3" 
                    class="form-control required"
                ></textarea>
                
            </div>
<div
        class='form-group form-group-default '
        id='group_anexos'
>
    <label title=''>
        ANEXOS    </label>
    <div class="" id="dropzone_anexos"></div>
    <input
            type="hidden"
            class=""
            name="anexos"
    >
</div>
<script data-baseurl="<?= $rootPath ?>">
    $(function () {
        let baseUrl = $('script[data-baseurl]').data('baseurl');
        let options = {"tipos":".pdf,.doc,.docx,.jpg,.jpeg,.gif,.png,.bmp,.xls,.xlsx,.ppt","longitud":"3","cantidad":"3"};
        let loadeddropzone_anexos= [];

        $("#dropzone_anexos").addClass('dropzone');
        let dropzone_anexos = new Dropzone("#dropzone_anexos", {
            url: baseUrl + 'app/temporal/cargar_anexos.php',
            dictDefaultMessage: 'Haga clic para elegir un archivo o Arrastre acá el archivo.',
            maxFilesize: options.longitud,
            maxFiles: options.cantidad,
            acceptedFiles: options.tipos,
            addRemoveLinks: true,
            dictRemoveFile: 'Eliminar',
            dictFileTooBig: 'Tamaño máximo {{maxFilesize}} MB',
            dictMaxFilesExceeded: `Máximo ${options.cantidad} archivos`,
            params: {
                token: localStorage.getItem('token'),
                key: localStorage.getItem('key'),
                dir: ' mesa_ayuda'
            },
            paramName: 'file',
            init: function () {
                
                this.on('success', function (file, response) {
                    response = JSON.parse(response);

                    if (response.success) {
                        response.data.forEach(e => {
                            loadeddropzone_anexos.push(e);
                        });
                        $("[name='anexos']").val(
                            loadeddropzone_anexos.join(',')
                        )
                        // Download link
                        var anchorEl = document.createElement('a');
                        anchorEl.setAttribute('href', baseUrl + response.data[0]);
                        anchorEl.setAttribute('target', '_blank');
                        anchorEl.innerHTML = "Descargar";
                        anchorEl.classList.add('dz-remove');
                        file.previewTemplate.appendChild(anchorEl);

                    } else {
                        top.notification({
                            type: 'error',
                            message: response.message
                        });
                    }
                });

                this.on('removedfile', function (file) {
                    if (file.route) { //si elimina un anexo cargado antes
                        var index = loadeddropzone_anexos.findIndex(route => route == file.route);
                    } else {//si elimina un anexo recien cargado
                        var index = loadeddropzone_anexos.findIndex(route => file.status == 'success' && route.indexOf(file.upload.filename) != -1);
                    }

                    loadeddropzone_anexos = loadeddropzone_anexos.filter((e, i) => i != index);
                    $("[name='anexos']").val(
                        loadeddropzone_anexos.join(',')
                    );
                                    });

                this.on('maxfilesexceeded', function () {
                    $('.dz-error').remove();
                    top.notification({
                        type: 'error',
                        message: 'Ha superado el número máximo de anexos permitidos'
                    });
                });
            }
        });
    });
</script>
<input type='hidden' name='estado_ticket' value='1'>
<input type='hidden' name='responsable' value=''>
<input type='hidden' name='clasificacion' value=''>
<input type='hidden' name='anterior' value='<?= $_REQUEST['anterior'] ?>'>
					<input type='hidden' name='campo_descripcion' value='9493'>
					<input type='hidden' name='documentId' value='<?= $documentId ?>'>
					<input type='hidden' id='tipo_radicado' name='tipo_radicado' value='mesa_ayuda'>
					<input type='hidden' name='formatId' value='54'>
					<input type='hidden' name='tabla' value='ft_mesa_ayuda'>
					<input type='hidden' name='formato' value='mesa_ayuda'>
					<div class='form-group px-0 pt-3' id='form_buttons'><button class='btn btn-complete' id='save_document' type='button'>Continuar</button><div class='progress-circle-indeterminate d-none' id='spiner'></div></div>                    </form>
                </div>
            </div>
            </div>

            <?= jsTheme() ?>            <?= icons() ?>            <?= moment() ?>            <?= select2() ?>            <?= validate() ?>            <?= ckeditor() ?>            <?= jqueryUi() ?>            <?= fancyTree(true) ?>            <?= dateTimePicker() ?>            <?= dropzone() ?>
                <?php
        if ($documentId) {
            $additionalParameters = $FtMesaAyuda->getRouteParams(FtMesaAyuda::SCOPE_ROUTE_PARAMS_EDIT);
        } else {
            $additionalParameters = $FtMesaAyuda->getRouteParams(FtMesaAyuda::SCOPE_ROUTE_PARAMS_ADD);
        }
        $params = json_encode(array_merge(
            $_REQUEST,
            $additionalParameters,
            ['baseUrl' => ABSOLUTE_SAIA_ROUTE]
        ));
    ?>            <script>
                $(function() {
                    let baseUrl = 'https://modulos.netsaia.com/saia_ma/saia_2019/';
                    let file = 'app/modules/back_mesa_ayuda/formatos/mesa_ayuda/funciones.js';
                    $.getScript(baseUrl + file, () => {
                        window.routeParams = <?= $params ?>;
                        if (+'<?= $documentId ?>') {
                            edit(<?= $params ?>)
                        } else {
                            add(<?= $params ?>)
                        }
                    });

                    $("#add_item").click(function() {
                        checkForm((data) => {
                            let options = top.window.modalOptions;
                            top.window.modalOptions = null;
                            top.topModal(options)
                        })
                    });

                    $("#save_item").click(function() {
                        checkForm((data) => {
                            top.successModalEvent(data);
                        })
                    });

                    $("#save_document").click(function() {
                        checkForm((data) => {
                            let route = baseUrl + "views/documento/index_acordeon.php?";
                            route += $.param(data);
                            window.location.href = route;
                        })
                    });

                    function checkForm(callback) {
                        $("#formulario_formatos").validate({
                            ignore: [],
                            submitHandler: function(form) {
                                $("#form_buttons").find('button,#spiner').toggleClass('d-none');

                                executeEvents(callback);
                            },
                            invalidHandler: function() {
                                $("#save_document").show();
                                $("#boton_enviando").remove();
                            }
                        });
                        $("#formulario_formatos").trigger('submit');
                    }

                    function executeEvents(callback) {
                        let documentId = $("[name='documentId']").val();

                        (+documentId ? beforeSendEdit() : beforeSendAdd())
                        .then(r => {
                            sendData()
                                .then(requestResponse => {
                                    (+documentId ? afterSendEdit(requestResponse) : afterSendAdd(requestResponse))
                                    .then(r => {
                                            callback(requestResponse.data);
                                        })
                                        .catch(message => {
                                            fail(message);
                                        })
                                }).catch(message => {
                                    fail(message);
                                });
                        }).catch(message => {
                            fail(message);
                        });
                    }

                    function sendData() {
                        return new Promise((resolve, reject) => {
                            let data = $('#formulario_formatos').serialize() + '&' +
                                $.param({
                                    key: localStorage.getItem('key'),
                                    token: localStorage.getItem('token')
                                });

                            $.post(baseUrl + 'app/documento/guardar_ft.php',
                                data,
                                function(response) {
                                    if (response.success) {
                                        resolve(response)
                                    } else {
                                        reject(response.message);
                                    }
                                },
                                'json'
                            );
                        });
                    }

                    function fail(message) {
                        $("#form_buttons").find('button,#spiner').toggleClass('d-none');
                        top.notification({
                            message: message,
                            type: 'error',
                            title: 'Error!'
                        });
                    }
                });
            </script>
                <?php AccionController::execute(
        AccionController::ACTION_ADD,
        AccionController::BEFORE_MOMENT,
        $FtMesaAyuda ?? null,
        $Formato
    ) ?>
</body>

</html>