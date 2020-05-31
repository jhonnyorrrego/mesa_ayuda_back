<?php

declare(strict_types=1);

namespace Saia\mesa_ayuda\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200425215229 extends AbstractMigration
{
    use TMigrations;
    
    public $nombreFormato = 'mesa_ayuda';
    public $etiquetaFormato = 'Mesa de ayuda';
    public $idFormato;
    
    public function getDescription() : string
    {
        return 'Creacion de tablas modulos, reportes del proceso de mesa de ayuda';
    }

    public function up(Schema $schema) : void
    {        
        $this -> init();
        $this -> idFormato = $this -> createFormat();
        $this -> createFields();
        $this -> createBusquedas();
        $this -> createConfig($schema);
        $this -> createModules();
    }
    
    public function createModules()
    {
        $name = $this -> nombreFormato;
        
        $sql = "SELECT idmodulo FROM modulo WHERE nombre='modulo_formatos'";
        $moduloFormatos = $this->connection->executeQuery($sql)->fetchAll();

        if (!$moduloFormatos[0]['idmodulo']) {
            $this->abortIf(true, "El modulo modulo_formatos NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "crear_{$name}",
            'tipo' => '2',
            'imagen' => NULL,
            'etiqueta' => $this -> etiquetaFormato,
            'enlace' => "app/modules/back_{$name}/formatos/{$name}/adicionar.php",
            'cod_padre' => $moduloFormatos[0]["idmodulo"],
            'orden' => 1,
            'color' => ''
        ];

        $id = $this->createModulo($data, "crear_{$name}");
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "agrupador_{$name}",
            'tipo' => '0',
            'imagen' => 'fa fa-life-saver',
            'etiqueta' => $this -> etiquetaFormato,
            'enlace' => '',
            'cod_padre' => '0',
            'orden' => 5,
            'color' => 'bg-warning'
        ];
        
        $idAgrupador = $this->createModulo($data, "agrupador_{$name}");
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "reporte_tickets",
            'tipo' => '1',
            'imagen' => 'fa fa-bar-chart-o',
            'etiqueta' => 'Reportes',
            'enlace' => '',
            'cod_padre' => $idAgrupador,
            'orden' => 1,
            'color' => ''
        ];
        
        $idReporteTickets = $this->createModulo($data, "reporte_tickets");
        
        //Creando modulo reporte_tickets_pendientes -----------------
        $sql = "SELECT idbusqueda_componente,etiqueta FROM busqueda_componente WHERE nombre='tickets_pendientes' order by idbusqueda_componente desc";
        $componente = $this->connection->executeQuery($sql)->fetchAll();

        if (!$componente[0]['idbusqueda_componente']) {
            $this->abortIf(true, "El componente tickets_pendientes NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "reporte_tickets_pendientes",
            'tipo' => '2',
            'imagen' => 'fa fa-bar-chart-o',
            'etiqueta' => 'Tickets pendientes',
            'enlace' => 'views/dashboard/kaiten_dashboard.php?panels=[{"kConnector": "iframe","url": "views/buzones/grilla.php?idbusqueda_componente=' . $componente[0]["idbusqueda_componente"] . '","kTitle":"' . $componente[0]["etiqueta"] . '"}]',
            'cod_padre' => $idReporteTickets,
            'orden' => 1,
            'color' => ''
        ];
        
        $id = $this->createModulo($data, "reporte_tickets_pendientes");
        //Fin reporte_tickets_pendientes -----------------------
        
        //Creando modulo reporte_tickets_clasificados -----------------
        /*$sql = "SELECT idbusqueda_componente,etiqueta FROM busqueda_componente WHERE nombre='tickets_clasificados' order by idbusqueda_componente desc";
        $componente = $this->connection->executeQuery($sql)->fetchAll();

        if (!$componente[0]['idbusqueda_componente']) {
            $this->abortIf(true, "El componente tickets_clasificados NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "reporte_tickets_clasificados",
            'tipo' => '2',
            'imagen' => 'fa fa-bar-chart-o',
            'etiqueta' => 'Tickets clasificados',
            'enlace' => 'views/dashboard/kaiten_dashboard.php?panels=[{"kConnector": "iframe","url": "views/buzones/grilla.php?idbusqueda_componente=' . $componente[0]["idbusqueda_componente"] . '","kTitle":"' . $componente[0]["etiqueta"] . '"}]',
            'cod_padre' => $idReporteTickets,
            'orden' => 2,
            'color' => ''
        ];
        
        $id = $this->createModulo($data, "reporte_tickets_clasificados");*/
        //Fin reporte_tickets_clasificados -----------------------
        
        //Creando modulo reporte_tickets_tarea -----------------
        /*$sql = "SELECT idbusqueda_componente,etiqueta FROM busqueda_componente WHERE nombre='tickets_tarea' order by idbusqueda_componente desc";
        $componente = $this->connection->executeQuery($sql)->fetchAll();

        if (!$componente[0]['idbusqueda_componente']) {
            $this->abortIf(true, "El componente tickets_tarea NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "reporte_tickets_tarea",
            'tipo' => '2',
            'imagen' => 'fa fa-bar-chart-o',
            'etiqueta' => 'Tickets asignado',
            'enlace' => 'views/dashboard/kaiten_dashboard.php?panels=[{"kConnector": "iframe","url": "views/buzones/grilla.php?idbusqueda_componente=' . $componente[0]["idbusqueda_componente"] . '","kTitle":"' . $componente[0]["etiqueta"] . '"}]',
            'cod_padre' => $idReporteTickets,
            'orden' => 2,
            'color' => ''
        ];
        
        $id = $this->createModulo($data, "reporte_tickets_tarea");*/
        //Fin reporte_tickets_tarea -----------------------
        
        //Creando modulo reporte_tickets_proceso -----------------
        /*$sql = "SELECT idbusqueda_componente,etiqueta FROM busqueda_componente WHERE nombre='tickets_tarea_proceso' order by idbusqueda_componente desc";
        $componente = $this->connection->executeQuery($sql)->fetchAll();

        if (!$componente[0]['idbusqueda_componente']) {
            $this->abortIf(true, "El componente tickets_tarea_proceso NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "reporte_tickets_proceso",
            'tipo' => '2',
            'imagen' => 'fa fa-bar-chart-o',
            'etiqueta' => 'Tickets en proceso',
            'enlace' => 'views/dashboard/kaiten_dashboard.php?panels=[{"kConnector": "iframe","url": "views/buzones/grilla.php?idbusqueda_componente=' . $componente[0]["idbusqueda_componente"] . '","kTitle":"' . $componente[0]["etiqueta"] . '"}]',
            'cod_padre' => $idReporteTickets,
            'orden' => 2,
            'color' => ''
        ];
        
        $id = $this->createModulo($data, "reporte_tickets_proceso");*/
        //Fin reporte_tickets_proceso -----------------------
        
        //Creando modulo reporte_tickets_terminado -----------------
        /*$sql = "SELECT idbusqueda_componente,etiqueta FROM busqueda_componente WHERE nombre='tickets_tarea_terminada' order by idbusqueda_componente desc";
        $componente = $this->connection->executeQuery($sql)->fetchAll();

        if (!$componente[0]['idbusqueda_componente']) {
            $this->abortIf(true, "El componente tickets_tarea_terminada NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "reporte_tickets_terminado",
            'tipo' => '2',
            'imagen' => 'fa fa-bar-chart-o',
            'etiqueta' => 'Tickets terminados',
            'enlace' => 'views/dashboard/kaiten_dashboard.php?panels=[{"kConnector": "iframe","url": "views/buzones/grilla.php?idbusqueda_componente=' . $componente[0]["idbusqueda_componente"] . '","kTitle":"' . $componente[0]["etiqueta"] . '"}]',
            'cod_padre' => $idReporteTickets,
            'orden' => 2,
            'color' => ''
        ];
        
        $id = $this->createModulo($data, "reporte_tickets_terminado");*/
        //Fin reporte_tickets_terminado -----------------------
        
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "configuracion_tickets",
            'tipo' => '1',
            'imagen' => 'fa fa-gears',
            'etiqueta' => 'Configuracion',
            'enlace' => '',
            'cod_padre' => $idAgrupador,
            'orden' => 2,
            'color' => ''
        ];
        
        $idConfiguracion = $this->createModulo($data, "configuracion_tickets");
        
        $sql = "SELECT idbusqueda_componente,etiqueta FROM busqueda_componente WHERE nombre='clasificacion_configuracion' order by idbusqueda_componente desc";
        $componente = $this->connection->executeQuery($sql)->fetchAll();

        if (!$componente[0]['idbusqueda_componente']) {
            $this->abortIf(true, "El componente clasificacion_configuracion NO existe");
        }
        
        $data = [
            'pertenece_nucleo' => '0',
            'nombre' => "clasificaciones_tickets",
            'tipo' => '2',
            'imagen' => 'fa fa-gears',
            'etiqueta' => 'Clasificaciones',
            'enlace' => 'views/dashboard/kaiten_dashboard.php?panels=[{"kConnector":"iframe","url":"views/buzones/grilla.php?idbusqueda_componente=' . $componente[0]["idbusqueda_componente"] . '","kTitle":"' . $componente[0]["etiqueta"] . '"}]',
            'cod_padre' => $idConfiguracion,
            'orden' => 1,
            'color' => ''
        ];
        
        $idConfiguracion = $this->createModulo($data, "clasificaciones_tickets");
    }
    
    public function createFormat()
    {
        $name = $this -> nombreFormato;
        
        $idContador = $this->connection->lastInsertId();

        $sql = "SELECT idfuncionario FROM funcionario WHERE login='cerok'";
        $funcionario = $this->connection->executeQuery($sql)->fetchAll();

        if (!$funcionario[0]['idfuncionario']) {
            $this->abortIf(true, "El funcionario cerok NO existe");
        }
        
        $sql = "SELECT idcontador FROM contador WHERE nombre='{$name}'";
        $busquedaContador = $this->connection->executeQuery($sql)->fetchAll();
        
        if (!$busquedaContador[0]['idcontador']) {
            $this->connection->insert('contador', [
                'nombre' => $this -> nombreFormato,
                'consecutivo' => '1',
                'reiniciar_cambio_anio' => '0',
                'etiqueta_contador' => $this -> etiquetaFormato,
                'post' => NULL,
                'estado' => '1'
            ]);
        }
        
        $sql = "SELECT idcategoria_formato FROM categoria_formato WHERE nombre like 'Tr%mites generales'";
        $categoriaFormato = $this->connection->executeQuery($sql)->fetchAll();

        if (!$categoriaFormato[0]['idcategoria_formato']) {
            $this->abortIf(true, "La categoria del formato tramites generales NO existe");
        }

        $data = [
            'nombre' => $name,
            'etiqueta' => $this -> etiquetaFormato,
            'cod_padre' => 0,
            'contador_idcontador' => $idContador,
            'nombre_tabla' => "ft_{$name}",
            'ruta_mostrar' => "app/modules/back_{$name}/formatos/{$name}/mostrar.php",
            'ruta_editar' => "app/modules/back_{$name}/formatos/{$name}/editar.php",
            'ruta_adicionar' => "app/modules/back_{$name}/formatos/{$name}/adicionar.php",
            'ruta_buscar' => "app/modules/back_{$name}/formatos/{$name}/buscar.php",
            'encabezado' => 1,
            'cuerpo' => '<p style="text-align:right">{*mostrar_qr*}</p>
<table border="1" cellpadding="1" cellspacing="1" class="table table-bordered" style="width:100%">
  <tbody>
    <tr>
      <td colspan="2" style="text-align:center"><strong>Descripci&oacute;n del ticket</strong></td>
    </tr>
    <tr>
      <td colspan="2" style="text-align:justify">{*descripcion*}</td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Fecha de solicitud</strong></td>
      <td style="width:70%">&nbsp;{*fecha_aprobacion*}</td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Solicitante</strong></td>
      <td style="width:70%">&nbsp;{*creador_documento*}</td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Anexos digitales</strong></td>
      <td style="width:70%">&nbsp;{*anexos*}</td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Clasificaci&oacute;n</strong></td>
      <td style="width:70%">&nbsp;{*getClasificacion*}</td>
    </tr>
    <tr>
      <td style="width:30%"><strong>&nbsp;Estado</strong></td>
      <td style="width:70%">&nbsp;{*getEstadoTicket*}</td>
    </tr>
  </tbody>
</table>
<p>{*mostrar_estado_proceso*}</p>',
            'pie_pagina' => 0,
            'margenes' => '25,25,25,25',
            'orientacion' => 0,
            'papel' => 'Letter',
            //'exportar' => 'mpdf',
            'funcionario_idfuncionario' => $funcionario[0]['idfuncionario'],
            'detalle' => 0,
            'tipo_edicion' => 0,
            'item' => 0,
            'font_size' => 14,
            'mostrar_pdf' => 0,
            'orden' => NULL,
            //'firma_digital' => 0,
            'fk_categoria_formato' => $categoriaFormato[0]['idcategoria_formato'],
            //'funcion_predeterminada' => 0,
            'paginar' => 0,
            'pertenece_nucleo' => 0,
            'descripcion_formato' => 'Formulario para registrar requerimiento',
            'version' => 1,
            'module' => $name,
            'banderas' => 'e,asunto_padre'
        ];

        $this->connection->insert('formato', $data);

        return $this->connection->lastInsertId();
    }

    public function createFields()
    {
        $idFormato = $this -> idFormato;
        $name = $this -> nombreFormato;
        
        $data = [
            'anexos' => [
                'formato_idformato' => $idFormato,
                'nombre' => 'anexos',
                'etiqueta' => 'Anexos',
                'tipo_dato' => 'string',
                'longitud' => 255,
                'obligatoriedad' => '0',
                'valor' => '',
                'acciones' => 'a,e,b',
                'ayuda' => NULL,
                'predeterminado' => NULL,
                'banderas' => 'a',
                'etiqueta_html' => 'Attached',
                'orden' => 4,
                'fila_visible' => 1,
                'placeholder' => NULL,
                'longitud_vis' => NULL,
                'opciones' => '{"tipos":".pdf,.doc,.docx,.jpg,.jpeg,.gif,.png,.bmp,.xls,.xlsx,.ppt","longitud":"3","cantidad":"3"}',
                'listable' => 1
            ],
            'descripcion' => [
                'formato_idformato' => $idFormato,
                'nombre' => 'descripcion',
                'etiqueta' => 'Descripción',
                'tipo_dato' => 'string',
                'longitud' => 255,
                'obligatoriedad' => '1',
                'valor' => '',
                'acciones' => 'a,e,p',
                'ayuda' => NULL,
                'predeterminado' => NULL,
                'banderas' => '',
                'etiqueta_html' => 'Textarea',
                'orden' => 3,
                'fila_visible' => 1,
                'placeholder' => 'campo texto con formato',
                'longitud_vis' => NULL,
                'opciones' => '{"avanzado":false}',
                'listable' => 1
            ],
            'estado_ticket' => [
                'formato_idformato' => $idFormato,
                'nombre' => 'estado_ticket',
                'etiqueta' => 'Estado',
                'tipo_dato' => 'string',
                'longitud' => 255,
                'obligatoriedad' => '0',
                'valor' => '',
                'acciones' => 'a,e,b',
                'ayuda' => NULL,
                'predeterminado' => 1,
                'banderas' => '',
                'etiqueta_html' => 'Hidden',
                'orden' => 5,
                'fila_visible' => 1,
                'placeholder' => 'Campo hidden',
                'longitud_vis' => NULL,
                'opciones' => '',
                'listable' => 1
            ],
      'clasificacion' => [
                'formato_idformato' => $idFormato,
                'nombre' => 'clasificacion',
                'etiqueta' => 'Clasificacion',
                'tipo_dato' => 'string',
                'longitud' => 255,
                'obligatoriedad' => '0',
                'valor' => '',
                'acciones' => 'a,e,b',
                'ayuda' => NULL,
                'predeterminado' => NULL,
                'banderas' => '',
                'etiqueta_html' => 'Hidden',
                'orden' => 6,
                'fila_visible' => 1,
                'placeholder' => 'Campo hidden',
                'longitud_vis' => NULL,
                'opciones' => '',
                'listable' => 1
            ],
            'pre_clasificacion' => [
                'formato_idformato' => $idFormato,
                'nombre' => 'pre_clasificacion',
                'etiqueta' => 'Clasificación',
                'tipo_dato' => 'string',
                'longitud' => 255,
                'obligatoriedad' => '0',
                'valor' => '1,1;2,2;3,3;4,4',
                'acciones' => 'a,e',
                'ayuda' => NULL,
                'predeterminado' => NULL,
                'banderas' => '',
                'etiqueta_html' => 'Select',
                'orden' => 2,
                'fila_visible' => 1,
                'placeholder' => 'seleccionar..',
                'longitud_vis' => NULL,
                'opciones' => '',
                'listable' => 1
            ],
            'pre_clasificacion_json' => [
                'formato_idformato' => $idFormato,
                'nombre' => 'pre_clasificacion_json',
                'etiqueta' => 'Clasificación',
                'tipo_dato' => 'text',
                'longitud' => NULL,
                'obligatoriedad' => '0',
                'valor' => '',
                'acciones' => '',
                'ayuda' => NULL,
                'predeterminado' => NULL,
                'banderas' => '',
                'etiqueta_html' => 'SystemField',
                'orden' => '0',
                'fila_visible' => 1,
                'placeholder' => '',
                'longitud_vis' => NULL,
                'opciones' => '',
                'listable' => '0'
            ],
            'idft_' . $name => [
                'formato_idformato' => $idFormato,
                'nombre' => 'idft_' . $name,
                'etiqueta' => $name,
                'tipo_dato' => 'integer',
                'longitud' => '11',
                'obligatoriedad' => '1',
                'valor' => '',
                'acciones' => 'a,e',
                'ayuda' => NULL,
                'predeterminado' => NULL,
                'banderas' => 'ai,pk',
                'etiqueta_html' => 'Hidden',
                'orden' => '0',
                'fila_visible' => 1,
                'placeholder' => '',
                'longitud_vis' => NULL,
                'opciones' => '',
                'listable' => '0'
            ]
        ];
        
        foreach ($data as $field) {            
            $idCampoFormato = $this->connection->insert('campos_formato', $field);
            
            if($field["nombre"] == 'pre_clasificacion' && $idCampoFormato){
              $idCampoClasificacion = $this->connection->lastInsertId();
            }
        }
        
        $data = [
            'llave' => "1",
            'valor' => ' ',
            'fk_campos_formato' => $idCampoClasificacion,
            'estado' => '1'
        ];
        
        $this->connection->insert('campo_opciones', $data);
    }

    public function createBusquedas()
    {
        $name = $this -> nombreFormato;
        
        //Se crea la busqueda tickets
        $busqueda = [
            'nombre' => 'tickets',
            'etiqueta' => 'Tickets',
            'estado' => 1,
            'campos' => 'a.fecha,a.numero',
            'tablas' => 'documento a',
            'ruta_libreria' => "app/modules/back_{$name}/reportes/librerias.php",
            'ruta_libreria_pantalla' => "views/modules/{$name}/views/reportes/funciones_reporte_js.php",
            'cantidad_registros' => 30,
            'tipo_busqueda' => 2
        ];
        $idbusqueda = $this->createBusqueda($busqueda, 'tickets');
        
        //Creando el componente tickets_pendientes------------------------------------
        $nombreComponente = 'tickets_pendientes';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Tickets pendientes',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Ticket","field":"{*verDocumentoTicket@iddocumento,numero*}","align":"center"},{"title":"Fecha","field":"{*fecha*}","align":"center"},{"title":"Solicitante","field":"{*mostrarUsuarioTicket@dependencia*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Clasificaci&oacute;n","field":"{*mostrarClasificacionTicket@id*}","align":"center"},{"title":"Vencimiento","field":"{*vencimientoTicket@fecha,tipo_dias,cant_dias,estado_ticket*}","align":"center"},{"title":"Estado","field":"{*mostrarEstadoTicket@id,estado_ticket*}","align":"center"},{"title":"Cantidad tareas","field":"{*contadorTareaTicket@iddocumento*}","align":"center"},{"title":"Acciones","field":"{*optionsTickets@id,iddocumento*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => 'b.descripcion,b.idft_mesa_ayuda as id,b.clasificacion,b.dependencia,c.tipo_dias,c.cant_dias,b.estado_ticket',
            'tablas_adicionales' => 'ft_mesa_ayuda b join ma_clasificacion c on b.clasificacion=c.idma_clasificacion join ma_clasificacion d on c.cod_padre=d.idma_clasificacion',
            'ordenado_por' => 'a.fecha',
            'direccion' => 'desc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => NULL,
            'llave' => 'a.iddocumento'
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets pendientes
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.iddocumento=b.documento_iddocumento {*filtrarResponsableTicket*}",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);
        //Fin tickets_pendientes-----------------------------------------------------
        
        //Creando el componente tickets_pendientes--------------------------------------
        /*$nombreComponente = 'tickets_pendientes';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Tickets pendientes',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Ticket","field":"{*verDocumentoTicket@iddocumento,numero*}","align":"center"},{"title":"Fecha","field":"{*fecha*}","align":"center"},{"title":"Solicitante","field":"{*mostrarUsuarioTicket@dependencia*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Clasificacion","field":"{*mostrarClasificacionTicket@clasificacion*}","align":"center"},{"title":"Clasificar","field":"{*listarClasificacionesTicket@id,iddocumento*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => 'b.descripcion,b.idft_mesa_ayuda as id,b.clasificacion,b.dependencia',
            'tablas_adicionales' => 'ft_mesa_ayuda b',
            'ordenado_por' => 'a.fecha',
            'direccion' => 'desc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => NULL,
            'llave' => 'a.iddocumento'
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets pendientes
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.iddocumento=b.documento_iddocumento and (b.estado_ticket is null or b.estado_ticket='' or b.estado_ticket=1)",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);*/
        //Fin tickets_pendientes--------------------------------------------------------
        
        //Creando el componente tickets_clasificados------------------------------------
        /*$nombreComponente = 'tickets_clasificados';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Tickets clasificados',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Ticket","field":"{*verDocumentoTicket@iddocumento,numero*}","align":"center"},{"title":"Fecha","field":"{*fecha*}","align":"center"},{"title":"Solicitante","field":"{*mostrarUsuarioTicket@dependencia*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Clasificaci&oacute;n","field":"{*mostrarClasificacionTicket@clasificacion*}","align":"center"},{"title":"Vencimiento","field":"{*vencimientoTicket@fecha,tipo_dias,cant_dias,estado_ticket*}","align":"center"},{"title":"Clasificar","field":"{*listarClasificacionesTicket@id,iddocumento*}","align":"center"},{"title":"Acciones","field":"{*accionesTicket@iddocumento*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => 'b.descripcion,b.idft_mesa_ayuda as id,b.clasificacion,b.dependencia,c.tipo_dias,c.cant_dias,b.estado_ticket',
            'tablas_adicionales' => 'ft_mesa_ayuda b join ma_clasificacion c on b.clasificacion=c.idma_clasificacion join ma_clasificacion d on c.cod_padre=d.idma_clasificacion',
            'ordenado_por' => 'a.fecha',
            'direccion' => 'desc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => NULL,
            'llave' => 'a.iddocumento'
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets clasificados
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.iddocumento=b.documento_iddocumento and b.estado_ticket=2 {*filtrarResponsableTicket*}",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);*/
        //Fin tickets_clasificados-----------------------------------------------------
        
        //Creando el componente tickets_tarea------------------------------------
       /* $nombreComponente = 'tickets_tarea';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Tickets Asignado',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Ticket","field":"{*verDocumentoTicket@iddocumento,numero*}","align":"center"},{"title":"Fecha","field":"{*fecha*}","align":"center"},{"title":"Usuario","field":"{*mostrarUsuarioTicket@dependencia*}","align":"center"},{"title":"Clasificaci&oacute;n","field":"{*mostrarClasificacionTicket@clasificacion*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"center"},{"title":"Cantidad tareas","field":"{*contadorTareaTicket@iddocumento*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => 'b.descripcion,b.idft_mesa_ayuda as id,b.clasificacion,b.dependencia',
            'tablas_adicionales' => 'ft_mesa_ayuda b join ma_clasificacion c on b.clasificacion=c.idma_clasificacion join ma_clasificacion d on c.cod_padre=d.idma_clasificacion',
            'ordenado_por' => 'a.fecha',
            'direccion' => 'desc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => NULL,
            'llave' => 'a.iddocumento'
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets clasificados
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.iddocumento=b.documento_iddocumento and b.estado_ticket=3 {*filtrarResponsableTicket*}",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);*/
        //Fin tickets_tarea-----------------------------------------------------
        
        //Creando el componente tickets_tarea_proceso------------------------------------
        /*$nombreComponente = 'tickets_tarea_proceso';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Tickets en proceso',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Ticket","field":"{*verDocumentoTicket@iddocumento,numero*}","align":"center"},{"title":"Fecha","field":"{*fecha*}","align":"center"},{"title":"Solicitante","field":"{*mostrarUsuarioTicket@dependencia*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Clasificaci&oacute;n","field":"{*mostrarClasificacionTicket@clasificacion*}","align":"center"},{"title":"Vencimiento","field":"{*vencimientoTicket@fecha,tipo_dias,cant_dias,estado_ticket*}","align":"center"},{"title":"Cantidad tareas","field":"{*contadorTareaTicket@iddocumento*}","align":"center"},{"title":"Acciones","field":"{*accionesTicket@iddocumento*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => 'b.descripcion,b.idft_mesa_ayuda as id,b.clasificacion,b.dependencia,c.tipo_dias,c.cant_dias,b.estado_ticket',
            'tablas_adicionales' => 'ft_mesa_ayuda b join ma_clasificacion c on b.clasificacion=c.idma_clasificacion join ma_clasificacion d on c.cod_padre=d.idma_clasificacion',
            'ordenado_por' => 'a.fecha',
            'direccion' => 'desc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => NULL,
            'llave' => 'a.iddocumento'
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets clasificados
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.iddocumento=b.documento_iddocumento and b.estado_ticket=3 {*filtrarResponsableTicket*}",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);*/
        //Fin tickets_tarea_proceso-----------------------------------------------------
        
        //Creando el componente tickets_tarea_terminada------------------------------------
        /*$nombreComponente = 'tickets_tarea_terminada';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Tickets terminadas',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Ticket","field":"{*verDocumentoTicket@iddocumento,numero*}","align":"center"},{"title":"Fecha","field":"{*fecha*}","align":"center"},{"title":"Solicitante","field":"{*mostrarUsuarioTicket@dependencia*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Clasificaci&oacute;n","field":"{*mostrarClasificacionTicket@clasificacion*}","align":"center"},{"title":"Vencimiento","field":"{*vencimientoTicket@fecha,tipo_dias,cant_dias,estado_ticket*}","align":"center"},{"title":"Cantidad tareas","field":"{*contadorTareaTicket@iddocumento*}","align":"center"},{"title":"Acciones","field":"{*accionesTicket@iddocumento*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => 'b.descripcion,b.idft_mesa_ayuda as id,b.clasificacion,b.dependencia,c.tipo_dias,c.cant_dias,b.estado_ticket',
            'tablas_adicionales' => 'ft_mesa_ayuda b join ma_clasificacion c on b.clasificacion=c.idma_clasificacion join ma_clasificacion d on c.cod_padre=d.idma_clasificacion',
            'ordenado_por' => 'a.fecha',
            'direccion' => 'desc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => NULL,
            'llave' => 'a.iddocumento'
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets clasificados
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.iddocumento=b.documento_iddocumento and b.estado_ticket=4 {*filtrarResponsableTicket*}",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);*/
        //Fin tickets_tarea_terminada-----------------------------------------------------
        
        
        //Se crea la busqueda tickets
        $busqueda = [
            'nombre' => "configuracion_{$name}",
            'etiqueta' => 'Configuracion ' . $this -> etiquetaFormato,
            'estado' => 1,
            'campos' => '',
            'tablas' => '',
            'ruta_libreria' => "app/modules/back_{$name}/reportes/librerias_configuracion.php,app/cf/librerias.php",
            'ruta_libreria_pantalla' => "views/modules/{$name}/views/reportes/funciones_reporte_js.php,views/buzones/utilidades/acciones_cf.php",
            'cantidad_registros' => 20,
            'tipo_busqueda' => 2
        ];
        $idbusqueda = $this->createBusqueda($busqueda, "configuracion_{$name}");
        
        //Creando el componente clasificacion_configuracion------------------------------------
        $nombreComponente = 'clasificacion_configuracion';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Clasificaciones',
            'nombre' => $nombreComponente,
            'orden' => 1,
            'url' => NULL,
            'info' => '[{"title":"Clasificacion principal","field":"{*mostrarEnlaceSecundaria@idma_clasificacion,nombre*}","align":"center"},{"title":"Responsable","field":"{*mostrarResponsableClasificacion@responsables_json*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Estado","field":"{*estado*}","align":"center"},{"title":"Acciones","field":"{*options@idma_clasificacion,nombre_tabla*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => "a.idma_clasificacion,a.nombre,case a.estado when 1 then 'Activo' else 'inactivo' end as estado, 'ma_clasificacion' as nombre_tabla,a.cant_dias,a.responsables_json,a.descripcion",
            'tablas_adicionales' => 'ma_clasificacion a',
            'ordenado_por' => 'a.nombre',
            'direccion' => 'asc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => "views/modules/{$name}/views/configuracion/adicionar_clasificacion.php?table=ma_clasificacion",
            'llave' => NULL
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente . '&table=ma_clasificacion'
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets clasificados
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "a.cod_padre=0 or a.cod_padre is null",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);
        //Fin clasificacion_configuracion-----------------------------------------------------
        
        //Creando el componente clasificacion_secundaria_configuracion------------------------------------
        $nombreComponente = 'clasificacion_secundaria_configuracion';
        $busquedaComponente = [
            'busqueda_idbusqueda' => $idbusqueda,
            'etiqueta' => 'Clasificaciones',
            'nombre' => $nombreComponente,
            'orden' => 2,
            'url' => NULL,
            'info' => '[{"title":"Nombre","field":"{*nombre*}","align":"center"},{"title":"Tiempo de respuesta","field":"{*cant_dias*}","align":"center"},{"title":"Tipo de dias","field":"{*tipo_dias*}","align":"center"},{"title":"Descripcion","field":"{*descripcion*}","align":"left"},{"title":"Estado","field":"{*estado*}","align":"center"},{"title":"Acciones","field":"{*options@idma_clasificacion,nombre_tabla*}","align":"center"}]',
            'encabezado_componente' => NULL,
            'campos_adicionales' => "a.idma_clasificacion,a.nombre,case a.estado when 1 then 'Activo' else 'inactivo' end as estado, 'ma_clasificacion' as nombre_tabla,a.cant_dias,case a.tipo_dias when 1 then 'Dias calendario' when 2 then 'Dias habiles' end as tipo_dias,a.descripcion",
            'tablas_adicionales' => 'ma_clasificacion a',
            'ordenado_por' => 'a.nombre',
            'direccion' => 'asc',
            'agrupado_por' => NULL,
            'busqueda_avanzada' => NULL,
            'enlace_adicionar' => "views/modules/{$name}/views/configuracion/adicionar_clasificacion.php?table=ma_clasificacion",
            'llave' => NULL
        ];
        $idbusquedaComponente = $this->createBusquedaComponente($idbusqueda, $busquedaComponente, $nombreComponente);
        
        $this->connection->update('busqueda_componente', [
            'url' => 'views/buzones/grilla.php?idbusqueda_componente=' . $idbusquedaComponente . '&table=ma_clasificacion'
        ], [
            'idbusqueda_componente' => $idbusquedaComponente
        ]);
        
        //Se crea la condicion para el componente tickets clasificados
        $busquedaCondicion = [
            'fk_busqueda_componente' => $idbusquedaComponente,
            'codigo_where' => "{*filtroPadreSecundario*}",
            'etiqueta_condicion' => $nombreComponente
        ];
        $this->createBusquedaCondicion($idbusquedaComponente, $busquedaCondicion, $nombreComponente);
        //Fin tickets_tarea_terminada-----------------------------------------------------
    }
    
    public function createConfig($schema)
    {
        $table = $schema->createTable('ma_clasificacion');
        $table -> addColumn('idma_clasificacion', 'integer', [
            'autoincrement' => true
        ]);
        $table -> setPrimaryKey(['idma_clasificacion']);
        
        $table->addColumn('nombre', 'string', [
            'length' => 255
        ]);
        
        $table->addColumn('cod_padre', 'integer');
        $table->addColumn('estado', 'integer', [
            'default' => '1'
        ]);
        
        $table->addColumn('cant_dias', 'integer', [
            'notnull' => false
        ]);
        
        $table->addColumn('responsables', 'string', [
            'length' => 255,
            'notnull' => false
        ]);
        
        $table->addColumn('responsables_json', 'string', [
            'length' => 255,
            'notnull' => false
        ]);
        
    }

    public function down(Schema $schema) : void
    {
        $name = $this -> nombreFormato;
           
        $this->connection->delete('contador', [
            'nombre' => $this -> nombreFormato
        ]);

        
        $this -> deleteFormat($this -> nombreFormato, $schema);
        $this -> deleteBusqueda('tickets');
        $this -> deleteBusqueda('configuracion_' . $this -> nombreFormato);
        $schema -> dropTable('ma_clasificacion');
        
        $this -> deleteModulo('crear_mesa_ayuda');
        $this -> deleteModulo('agrupador_mesa_ayuda');
        $this -> deleteModulo('reporte_tickets');
        $this -> deleteModulo('reporte_tickets_pendientes');
        $this -> deleteModulo('reporte_tickets_clasificados');
        $this -> deleteModulo('reporte_tickets_tarea');
        $this -> deleteModulo('reporte_tickets_proceso');
        $this -> deleteModulo('reporte_tickets_terminado');
        
        $sql = "SELECT c.idcampo_opciones FROM formato a, campos_formato b, campo_opciones c WHERE a.nombre='{$name}' and a.idformato=b.formato_idformato and b.idcampos_formato=c.fk_campos_formato";
        $campoOpciones = $this->connection->executeQuery($sql)->fetchAll();
        
        foreach ($campoOpciones as $item) {
            $idcampoOpciones = $item['idcampo_opciones'];
            
            $this->connection->delete('campo_opciones', [
                'idcampo_opciones' => $idcampoOpciones
            ]);
        }
    }
}
