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

use Saia\controllers\JwtController;
use Saia\controllers\generador\ComponentFormGeneratorController;
use Saia\controllers\AccionController;
use Saia\models\formatos\Formato;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;

JwtController::check($_REQUEST["token"], $_REQUEST["key"]); 

$Formato = new Formato(20);
$documentId=$_REQUEST['documentId'] ?? 0;
$baseUrl=$Formato->isItem() ? ABSOLUTE_SAIA_ROUTE : $rootPath;

$FtMesaAyuda = FtMesaAyuda::findByDocumentId($documentId);

?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <meta charset="utf-8" />
    <title>SGDA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=10.0, shrink-to-fit=no" />
    <meta name="apple-mobile-web-app-capable" content="yes">

    <?= jquery() ?><?= bootstrap() ?><?= cssTheme() ?>
</head>

<body>
    <div class='container-fluid container-fixed-lg col-lg-8' style="overflow: auto;height:100vh">
        <div class='card card-default'>
            <div class='card-body'>
                <h5 class='text-black w-100 text-center'>
                    Mesa de Ayuda
                </h5>
                <form 
                    name='formulario_formatos' 
                    id='formulario_formatos' 
                    role='form' 
                    autocomplete='off' 
                    >
                    <input type='hidden' name='idft_mesa_ayuda' value='<?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'idft_mesa_ayuda',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='documento_iddocumento' value='<?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'documento_iddocumento',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='encabezado' value='<?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'encabezado',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='firma' value='<?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'firma',
                $FtMesaAyuda
            ) ?>'>

        <?php
        use Saia\controllers\SessionController;
        use Saia\core\DatabaseConnection;

        $selected = $FtMesaAyuda->dependencia ?? '';
        $query = DatabaseConnection::getQueryBuilder();
        $roles = $query
            ->select("dependencia as nombre, iddependencia_cargo, cargo")
            ->from("vfuncionario_dc")
            ->where("estado_dc = 1 and login = :login")
            ->andWhere(
                $query->expr()->lte('fecha_inicial', ':initialDate'),
                $query->expr()->gte('fecha_final', ':finalDate')
            )->setParameter(":login", SessionController::getLogin())
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

        <div class='form-group form-group-default form-group-default-select2 ' id='group_pre_clasificacion'>
            <label title=''>CLASIFICACIóN</label>
            <select class='full-width' name='pre_clasificacion' id='pre_clasificacion' >
            <option value=''>Por favor seleccione...</option>
        <option value='75' data-key='75'>
                 
            </option></select>
                <script>
                $(document).ready(function() {
                    $('#pre_clasificacion').select2();
                });
                </script>
        </div>            <script>
                $(function(){
                    $.post(
                        '<?= ABSOLUTE_SAIA_ROUTE ?>app/documento/consulta_seleccionado.php',
                        {
                            key: localStorage.getItem('key'),
                            token: localStorage.getItem('token'),
                            fieldId: 9198,
                            documentId: "<?= $documentId ?>"
                        },
                        function (response) {
                            if (response.success) {
                                if(response.data.selected.length){
                                    if(response.data.inactive.length){
                                        var item = response.data.inactive[0];
                                                              
                                        $('#pre_clasificacion').append(
                                            $("<option>", {
                                                value: item.id,
                                                text: item.label
                                            })
                                        );
                                    }
                                    $("[name='pre_clasificacion']")
                                        .val(response.data.selected)
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
            <div class="form-group form-group-default required" id="group_descripcion">
                <label title="">
                    DESCRIPCIóN
                </label>
                <textarea 
                    name="descripcion"
                    id="descripcion" 
                    rows="3" 
                    class="form-control required"
                ><?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'descripcion',
                $FtMesaAyuda
            ) ?></textarea>
                
            </div>
        <div class='form-group form-group-default ' id='group_anexos'>
            <label title=''>ANEXOS</label>
            <div class="" id="dropzone_anexos"></div>
            <input type="hidden" class="" name="anexos">
        </div>
        <script data-baseurl="<?= $rootPath ?>">
            $(function(){
                var baseUrl = $('script[data-baseurl]').data('baseurl');
                let options = {"tipos":".pdf,.doc,.docx,.jpg,.jpeg,.gif,.png,.bmp,.xls,.xlsx,.ppt","longitud":"3","cantidad":"3"}
                let loadeddropzone_anexos = [];
                $("#dropzone_anexos").addClass('dropzone');
                let dropzone_anexos = new Dropzone('#dropzone_anexos', {
                    url: baseUrl+'app/temporal/cargar_anexos.php',
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
                        dir: 'mesa_ayuda'
                    },
                    paramName: 'file',
                    init : function() {
                        $.post(baseUrl+'app/anexos/consultar_anexos_campo.php', {
                            token: localStorage.getItem('token'),
                            key: localStorage.getItem('key'),
                            fieldId: 9192,
                            documentId: "<?= $documentId ?>"
                        }, function(response){
                            if(response.success){
                                response.data.forEach(mockFile => {
                                    dropzone_anexos.removeAllFiles();
                                    dropzone_anexos.emit('addedfile', mockFile);
                                    dropzone_anexos.emit('thumbnail', mockFile, baseUrl + mockFile.route);
                                    dropzone_anexos.emit('complete', mockFile);

                                    loadeddropzone_anexos.push(mockFile.route);
                                });                        
                                $("[name='anexos']").val(loadeddropzone_anexos.join(','));
                                dropzone_anexos.options.maxFiles = options.cantidad - loadeddropzone_anexos.length;                        
                            }
                        }, 'json');

                        this.on('success', function(file, response) {
                            response = JSON.parse(response);

                            if (response.success) {
                                response.data.forEach(e => {
                                    loadeddropzone_anexos.push(e);
                                });
                                $("[name='anexos']").val(loadeddropzone_anexos.join(','))
                            } else {
                                top.notification({
                                    type: 'error',
                                    message: response.message
                                });
                            }
                        });

                        this.on('removedfile', function(file) {
                            if(file.route){ //si elimina un anexo cargado antes
                                var index = loadeddropzone_anexos.findIndex(route => route == file.route);
                            }else{//si elimina un anexo recien cargado
                                var index = loadeddropzone_anexos.findIndex(route => file.status == 'success' && route.indexOf(file.upload.filename) != -1);                                
                            }
                           
                            loadeddropzone_anexos = loadeddropzone_anexos.filter((e,i) => i != index);
                            $("[name='anexos']").val(loadeddropzone_anexos.join(','));
                            dropzone_anexos.options.maxFiles = options.cantidad - loadeddropzone_anexos.length;
                        });
                    }
                });
            });
        </script>
<input type='hidden' name='estado_ticket' value='<?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'estado_ticket',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='clasificacion' value='<?= Saia\controllers\generador\ComponentFormGeneratorController::callShowValue(
                'clasificacion',
                $FtMesaAyuda
            ) ?>'>
<input type='hidden' name='campo_descripcion' value='9186'>
					<input type='hidden' name='documentId' value='<?= $documentId ?>'>
					<input type='hidden' id='tipo_radicado' name='tipo_radicado' value='mesa_ayuda'>
					<input type='hidden' name='formatId' value='20'>
					<input type='hidden' name='tabla' value='ft_mesa_ayuda'>
					<input type='hidden' name='formato' value='mesa_ayuda'>
					<div class='form-group px-0 pt-3' id='form_buttons'><button class='btn btn-complete' id='save_document' type='button'>Continuar</button><div class='progress-circle-indeterminate d-none' id='spiner'></div></div>
                </form>
            </div>
        </div>
    </div>

    <?= jsTheme() ?>
    <?= icons() ?>
    <?= moment() ?>
    <?= select2() ?>
    <?= validate() ?>
    <?= ckeditor() ?>
    <?= jqueryUi() ?>
    <?= fancyTree(true) ?>
    <?= dateTimePicker() ?>
    <?= dropzone() ?>
   
    <?php
 
        if($documentId){
            $additionalParameters=$FtMesaAyuda->getRouteParams(FtMesaAyuda::SCOPE_ROUTE_PARAMS_EDIT); 
        }else{
            $additionalParameters=$FtMesaAyuda->getRouteParams(FtMesaAyuda::SCOPE_ROUTE_PARAMS_ADD); 
        }
        $params=array_merge($_REQUEST,$additionalParameters,['baseUrl'=> $baseUrl]);
    ?>
    <script>
        $(function() {
            $.getScript('<?= $baseUrl ?>app/modules/back_mesa_ayuda/formatos/mesa_ayuda/funciones.js', () => {
                window.routeParams=<?= json_encode($params) ?>;
                if (+'<?= $documentId ?>') {
                    edit(<?= json_encode($params) ?>)
                } else {
                    add(<?= json_encode($params) ?>)
                }
            });

            $("#add_item").click(function() {
                checkForm((data) => {
                    let options = top.window.modalOptions;
                    options.oldSource = null;
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
                    let route = "<?= $baseUrl ?>views/documento/index_acordeon.php?";
                    route += $.param(data);
                    window.location.href = route;
                })
            });

            function checkForm(callback){
                $("#formulario_formatos").validate({
                    ignore: [],
                    errorPlacement: function (error, element) {
                        let node = element[0];

                        if (
                            node.tagName == 'SELECT' &&
                            node.className.indexOf('select2') !== false
                        ) {
                            error.addClass('pl-3');
                            element.next().append(error);
                        } else {
                            error.insertAfter(element);
                        }
                    },
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

            function executeEvents(callback){
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

            function sendData(){
                return new Promise((resolve, reject) => {
                    let data = $('#formulario_formatos').serialize() + '&' +
                    $.param({
                        key: localStorage.getItem('key'),
                        token: localStorage.getItem('token')
                    });
    
                    $.post(
                        '<?= $baseUrl ?>app/documento/guardar_ft.php',
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

            function fail(message){
                $("#form_buttons").find('button,#spiner').toggleClass('d-none');
                top.notification({
                    message: message,
                    type: 'error',
                    title: 'Error!'
                });
            }
        });
    </script>
    <?= AccionController::execute(
        AccionController::ACTION_EDIT,
        AccionController::BEFORE_MOMENT,
        $FtMesaAyuda ?? null,
        $Formato
    ) ?>
</body>
</html>