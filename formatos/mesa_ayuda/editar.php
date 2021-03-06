<?php
$max_salida = 10;
$rootPath = $ruta = "";

while ($max_salida > 0) {
    if (is_file($ruta . "index.php")) {
        $rootPath = $ruta;
        break;
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

$Formato = new Formato(51);
$documentId = $_REQUEST['documentId'] ?? 0;
    $FtMesaAyuda = FtMesaAyuda::findByDocumentId($documentId);?><!DOCTYPE html>
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
                        <input type='hidden' name='documento_iddocumento' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'documento_iddocumento',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='encabezado' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'encabezado',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='firma' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'firma',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='idft_mesa_ayuda' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'idft_mesa_ayuda',
                $FtMesaAyuda
            ) ?>'>

        <?php
        $selected = $FtMesaAyuda->dependencia ?? '';
        $query = Saia\core\DatabaseConnection::getDefaultConnection()->createQueryBuilder();
        $roles = $query
            ->select("dependencia as nombre, iddependencia_cargo, cargo")
            ->from("vfuncionario_dc")
            ->where("estado_dc = 1 and login = :login")
            ->andWhere(
                $query->expr()->lte('fecha_inicial', ':initialDate'),
                $query->expr()->gte('fecha_final', ':finalDate')
            )->setParameter(":login", Saia\controllers\SessionController::getLogin())
            ->setParameter(':initialDate', new DateTime(), \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)
            ->setParameter(':finalDate', new DateTime(), \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)
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

        <div class='form-group form-group-default required' id='group_medio'>
            <label title=''>MEDIO</label>
            <div class='radio radio-success input-group'>
        <input 
                required
                type='radio'
                name='medio'
                id='medio0'
                value='261' 
                data-key='261' 
                aria-required='true'>
                <label for='medio0' class='mr-3 label-without-focus'>
                    Saia
                </label><input 
                required
                type='radio'
                name='medio'
                id='medio1'
                value='262' 
                data-key='262' 
                aria-required='true'>
                <label for='medio1' class='mr-3 label-without-focus'>
                    Correo
                </label><input 
                required
                type='radio'
                name='medio'
                id='medio2'
                value='263' 
                data-key='263' 
                aria-required='true'>
                <label for='medio2' class='mr-3 label-without-focus'>
                    Chat
                </label><input 
                required
                type='radio'
                name='medio'
                id='medio3'
                value='264' 
                data-key='264' 
                aria-required='true'>
                <label for='medio3' class='mr-3 label-without-focus'>
                    Telefono
                </label><input 
                required
                type='radio'
                name='medio'
                id='medio4'
                value='265' 
                data-key='265' 
                aria-required='true'>
                <label for='medio4' class='mr-3 label-without-focus'>
                    Pagina Web
                </label></div>
            <label id='medio-error' class='error' for='medio' style='display: none;'></label>
        </div>            <script>
                $(function(){
                    $.post(
                        '<?= ABSOLUTE_SAIA_ROUTE ?>app/documento/consulta_seleccionado.php',
                        {
                            key: localStorage.getItem('key'),
                            token: localStorage.getItem('token'),
                            fieldId: 9858,
                            documentId: "<?= $documentId ?>"
                        },
                        function (response) {
                            if (response.success) {
                                if(response.data.selected.length){
                                    if(response.data.inactive.length){
                                        var node = $("[name='medio']").parent();
                                        var inactive = response.data.inactive[0];
                                        var key = $("[name='medio']").length;

                                        node.append(
                                            $("<input>", {
                                                type : 'radio',
                                                name : 'medio',
                                                id : "medio"+key,
                                                value: inactive.id,
                                                "aria-required": 'true'
                                            }),
                                            $("<label>", {
                                                for: "medio"+key,
                                                class: "mr-3 label-without-focus",
                                                text: inactive.label
                                            })
                                        );
                                    }
                                    $("[name='medio'][value='"+response.data.selected+"']")
                                        .prop('checked', true)
                                        .trigger('change');
                                }
                            } else {
                                top.notification({
                                    type: 'error',
                                    message: response.message
                                });
                            }
                        },
                        'json'
                    );
                });
            </script>
<?= $FtMesaAyuda->pre_clasificacion_funcion_add(9847)?>
            <div class="form-group form-group-default required" id="group_descripcion">
                <label title="">
                    DESCRIPCIÓN
                </label>
                <textarea 
                    name="descripcion"
                    id="descripcion" 
                    rows="3" 
                    class="form-control required"
                ><?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'descripcion',
                $FtMesaAyuda
            ) ?></textarea>
                
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
        let options = {"tipos":".pdf,.doc,.docx,.jpg,.jpeg,.gif,.png,.bmp,.xls,.xlsx,.ppt","longitud":"3","cantidad":"3","ruta_consulta":"app\\\/anexos\\\/consultar_anexos_campo.php"};
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
                                $.post(baseUrl + options.ruta_consulta, {
                    token: localStorage.getItem('token'),
                    key: localStorage.getItem('key'),
                    fieldId: 9834,
                    documentId: <?= $documentId ?>                }, function (response) {
                    if (response.success) {
                        response.data.forEach(mockFile => {
                            var thumbnail = mockFile.thumbnail || mockFile.route;
                            var stringify = JSON.stringify({
                                success: 1,
                                data: [mockFile.route]
                            });
                            dropzone_anexos.removeAllFiles();
                            dropzone_anexos.emit('addedfile', mockFile);
                            dropzone_anexos.emit('thumbnail', mockFile, baseUrl + thumbnail);
                            dropzone_anexos.emit('complete', mockFile);
                            dropzone_anexos.emit('success', mockFile, stringify);
                        });

                        dropzone_anexos.options.maxFiles = options.cantidad - response.data.length;
                    }
                }, 'json');
                
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
                                        dropzone_anexos.options.maxFiles = options.cantidad - loadeddropzone_anexos.length;
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
<input type='hidden' name='responsable' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'responsable',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='estado_ticket' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'estado_ticket',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='clasificacion' value='<?= Saia\controllers\generator\component\ComponentBuilder::callShowValue(
                'clasificacion',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='documentId' value='<?= $documentId ?>'>
					<input type='hidden' id='tipo_radicado' name='tipo_radicado' value='mesa_ayuda'>
					<input type='hidden' name='formatId' value='51'>
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
        
        $isFormatRad = 0;
    ?>            <script>
                $(function() {
                    let baseUrl = 'https://modulos.netsaia.com/saia_ma/saia_2019/';
                    let file = 'app/modules/back_mesa_ayuda/formatos/mesa_ayuda/funciones.js';
                    let isFormatRad = <?= $isFormatRad ?>;
                    let params = <?= $params ?>;

                    $.getScript(baseUrl + file, () => {
                        window.routeParams = <?= $params ?>;
                        if (+'<?= $documentId ?>') {
                            edit(<?= $params ?>)
                        } else {
                            add(<?= $params ?>);
                            if (window.routeParams.padre){
                                $.post(
                                `${baseUrl}app/formato/consulta_ft_padre.php`,
                                {
                                    key: localStorage.getItem('key'),
                                    token: localStorage.getItem('token'),
                                    padre : window.routeParams.padre
                                },
                                function (response){
                                    let data = response.data;
                                    if(response.success){
                                        $(`input[name="${data.parentFormatTable}"]`).val(data.parentFtId);
                                    }
                                },
                                'json'
                            );
                            }
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

                            if (isFormatRad) {
                                let documentId = data.documentId;
                                let digitalizacion = $('input[name="digitalizacion"]:checked').val();
                                let orientacionSello = $('input[name="colilla"]:checked').data('key') || 0;

                                let dataUrl = {
                                    target: 'self',
                                    colilla_vertical: orientacionSello,
                                    documentId: documentId
                                };

                                let rutaOrientacion = +orientacionSello ?
                                    'views/colilla/colillaVertical.php?' :
                                    'views/colilla/colillaHorizontal.php?';

                                if (+digitalizacion) {
                                    dataUrl.enlace = `views/documento/digitalizar_paginas.php?documentId=${documentId}&librerias=1`;
                                }

                                let paramsUrl = $.param(dataUrl);
                                route = baseUrl + rutaOrientacion + paramsUrl;
                            }

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

                        (+documentId ? beforeSendEdit(<?= $params ?>) : beforeSendAdd(<?= $params ?>))
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

                    if (isFormatRad) {
                        if (typeof params.fk_rcmail_data != 'undefined') {
                            $.ajax({
                                url: `${baseUrl}app/modules/back_roundcube/app/request.php`,
                                type: 'POST',
                                dataType: 'json',
                                data: {
                                    token: localStorage.getItem('token'),
                                    key: localStorage.getItem('key'),
                                    method: 'getInfoRcmail',
                                    data: {
                                        id: params.fk_rcmail_data
                                    }
                                },
                                success: function (response) {
                                    $("#descripcion").val(response.asunto);
                                    loadAnexos(response.anexos_digitales);

                                }
                            });
                        }
                    }

                    function loadAnexos(anexos) {
                        let baseUrl = localStorage.getItem('baseUrl');

                        var myDropzone = Dropzone.forElement("#dropzone_anexos_digitales");
                        anexos.forEach(mockFile => {
                            var thumbnail = mockFile.thumbnail || mockFile.route;
                            var stringify = JSON.stringify({
                                success: 1,
                                data: [mockFile.route]
                            });
                            myDropzone.removeAllFiles();
                            myDropzone.emit('addedfile', mockFile);
                            myDropzone.emit('thumbnail', mockFile, baseUrl + thumbnail);
                            myDropzone.emit('complete', mockFile);
                            myDropzone.emit('success', mockFile, stringify);
                        });
                    }
                });
            </script>
                <?php AccionController::execute(
        AccionController::ACTION_EDIT,
        AccionController::BEFORE_MOMENT,
        $FtMesaAyuda ?? null,
        $Formato
    ) ?>
</body>

</html>