<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

use Doctrine\DBAL\Types\Type;
use Saia\core\DatabaseConnection;
use Saia\controllers\generator\component\ComponentBuilder;
use Saia\models\documento\ComentarioDocumento;
use Saia\controllers\DateController;

class FtMesaAyuda extends FtMesaAyudaProperties
{
    const ESTADO_PENDIENTE = 1;
    const ESTADO_PROCESO = 2;
    const ESTADO_TERMINADO = 3;
    
    public $estados = array(self::ESTADO_PENDIENTE => 'Pendiente', self::ESTADO_PROCESO => 'En proceso', self::ESTADO_TERMINADO => 'Finalizado');
    
    public function __construct($id = null)
    {
        parent::__construct($id);
    }
    
    public function getEstadoTicket(){
        $cadenaRetorno = '';
              
        $estadoAlmacenado = $this -> estado_ticket;
        if(!$estadoAlmacenado){
          $estadoAlmacenado = 1;
        }
        
        if($estadoAlmacenado == self::ESTADO_PENDIENTE){
            $cadenaRetorno = '<div class="badge badge-danger" style="">' . $this -> estados [$estadoAlmacenado] . '</div>';
        } else {
            $cadenaRetorno = '<div class="badge badge-success" style="">' . $this -> estados [$estadoAlmacenado] . '</div>';
        }
        
        return($cadenaRetorno);
    }
    
    public function getClasificacion(){
        $cadenaRetorno = '';
      
        $clasificacion = $this -> clasificacion;
        
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
    
    /**
     * Funcion ejecutada posterior al adicionar un Ticket
     *
     * @return boolean
     * @author Mauricio Orrego <mauricio.orrego@cerok.com>
     * @date 2020
     */
    public function afterAdd()
    {
        $clasificacion = $this -> clasificacion;
        
        $datosClasificacion = DatabaseConnection::getQueryBuilder()
        ->select('b.responsables')
        ->from('ma_clasificacion','a')
        ->leftJoin('a','ma_clasificacion','b','a.cod_padre=b.idma_clasificacion')
        ->where('a.idma_clasificacion = :idma')
        ->setParameter(':idma',$clasificacion, \Doctrine\DBAL\Types\Type::INTEGER)
        ->execute()->fetchAll();
        
        $this -> responsable = $datosClasificacion[0]["responsables"];
        $this -> update();
    }
    /**
     * Funcion ejecutada en el adicionar para crear el campo de Clasificacion
     *
     * @return boolean
     * @author Mauricio Orrego <mauricio.orrego@cerok.com>
     * @date 2020
     */
    public function pre_clasificacion_funcion_add()
    {
        $html = '';
        
        $html .= "<div class='form-group form-group-default form-group-default-select2 required' id='group_pre_clasificacion'>
            <label title=''>CLASIFICACIóN</label>
            <select class='full-width' name='pre_clasificacion' id='pre_clasificacion' required>
            <option value=''>Por favor seleccione...</option>
        <option value='' data-key='1'>
                 
            </option></select>
                <script>
                $(document).ready(function() {
                    $('#pre_clasificacion').select2();
                });
                </script>
        </div>";
        
        return $html;
    }
    /**
     * Funcion en el mostrar que organiza la informacion del ticket con los comentarios
     *
     * @return boolean
     * @author Mauricio Orrego <mauricio.orrego@cerok.com>
     * @date 2020
     */
    public function mostrarInfoTicket(){
        $descripcion = $this -> descripcion;
        $fecha = $this -> Documento -> getDateAttribute('fecha');
        $creador = $this -> Documento -> Funcionario -> getName();
        $anexos = ComponentBuilder::callShowValue('anexos',$this);
        //$anexos = 
        $radicado = $this -> Documento -> numero;
        
        $comments = ComentarioDocumento::findAllByAttributes([
            'fk_documento' => $this -> documento_iddocumento
        ]);
        $comments = array_reverse($comments);
        
        $html = '';
        
        $html .= "<input type='hidden' id='iddoc' value='" . $this -> documento_iddocumento . "'>";
        $html .= "<div class='row border-bottom p-2'>";
        
        $html .= "<div class='col-1 text-center'>";
        $html .= "<i class='fa fa-ticket fa-lg rounded-circle'></i>";
        $html .= "</div>";
        $html .= "<div class='col-11'>";
        $html .= "<div class='h3'>" . $descripcion . "</div>";
        $html .= "<div class='h5 pull-right'>Ticket # " . $radicado . "</div>";
        $html .= "<div class='pull-left'>" . $fecha . " - " . $creador . " - " . $this -> getEstadoTicket() . " - Clasificado en: " . $this -> getClasificacion() . " - Anexos: " . $anexos . "</div>";
        $html .= "</div>";
        $html .= "</div>";
        
        $html .= "<div class='row border-bottom p-2'>";
        $html .= "<div class='col-1 text-center'>";
        $html .= "<i class='fa fa-user-secret fa-lg rounded-circle'></i>";
        $html .= "</div>";
        $html .= "<div class='col-11'>";
        $html .= '<div class="form-group form-group-default"><label title="">DEJA TU COMENTARIO</label><textarea name="descripcion" id="descripcion" rows="3" class="form-control"></textarea></div>';
        $html .= '<span class="pull-left"></span>';
        $html .= '<button class="btn btn-complete pull-right" id="save_document" type="button">Guardar</button>';
        $html .= "</div>";
        $html .= "</div>";
        
        foreach ($comments as $ComentarioDocumento) {
            $imagen = "<i class='fa fa-question-circle fa-lg rounded-circle'></i>";
            $foto = $ComentarioDocumento->Funcionario->getImage('foto_recorte');
            if(file_exists($foto)){
                $imagen = '<img class="rounded-circle" src="' . $foto . '" style="width:32px;height:32px;">';
            }
          
            $html .= '<div class="row border-bottom p-2">';
            $html .= "<div class='col-1 text-center'>";
            $html .= $imagen;
            $html .= "</div>";
            $html .= "<div class='col-11'>";
            $html .= "<div class='h5'>" . $ComentarioDocumento->Funcionario->getName() . "</div>" . $ComentarioDocumento->comentario . "<span class='float-right hint-text'>" . DateController::convertDate($ComentarioDocumento->fecha) . "</span>";
            $html .= "</div>";
            $html .= "</div>";
        }
        
        
        
        return($html);
    }
}