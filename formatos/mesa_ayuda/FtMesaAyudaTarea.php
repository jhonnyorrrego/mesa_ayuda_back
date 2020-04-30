<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

use Doctrine\DBAL\Types\Type;
use Saia\core\model\Model;
use Saia\models\documento\Documento;
use Saia\models\tarea\Tarea;
use Saia\models\tarea\IExternalEventsTask;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;
use Saia\core\DatabaseConnection;

class FtMesaAyudaTarea implements IExternalEventsTask
{  
  protected $Tarea;
  protected $Instance;
  
  public function __construct(Model $Instance, Tarea $Tarea)
  {
      $this->Tarea = $Tarea;
      $this->Instance = $Instance;
  }  
  
  public function afterCreateTarea(): void
  {
    
  }
  public function afterUpdateTarea(): void
  {
    
  }
  public function afterDeleteTarea(): void
  {
    
  }
  public function afterCreateTareaAnexo(): void
  {
    
  }
  public function afterCreateTareaComentario(): void
  {
  }
  public function afterCreateTareaEstado(): void
  {
    if($DocumentoTarea = $this -> Tarea -> getDocument()){
      $this -> actualizarEstadoTareaTicket($DocumentoTarea -> Documento);
    }
  }
  public function afterCreateTareaEtiqueta(): void
  {
    
  }
  public function afterCreateTareaFuncionario(): void
  {
    
  }
  public function afterUpdateTareaFuncionario(): void
  {
    
  }
  public function afterCreateTareaNotificacion(): void
  {
    
  }
  public function afterCreateTareaPrioridad(): void
  {
    
  }
  public function afterCreateDocumentoTarea(): void
  {
    $this -> actualizarEstadoTareaTicketPendiente();
  }
  
  public function actualizarEstadoTareaTicketPendiente()
  {
    $Ft = $this->Instance->Documento->getFt();
    if($Ft -> estado_ticket == 2){
      $Ft -> estado_ticket = 3;
      $Ft -> update();
    }
  }
  
  public function actualizarEstadoTareaTicket(Documento $Documento)
  {
    $validEstadoTareas = true;
    
    $estadoTarea = $this -> Tarea -> getState();
    $Ft = $Documento -> getFt();
    
    if(intval($Ft -> estado_ticket) < 5){//Si el ticket aun no se ha finalizado
      //Busco en todas las tareas del documento si ya esta en estado finalizado
      //Si ya esta en estado finalizado, no se debe afectar mas los estados
      //Solo se actualizara los estados en el ft cuando el ticket no se haya finalizado
      $tareasDocumento = DatabaseConnection::getQueryBuilder()
          ->select('fk_tarea')
          ->from('documento_tarea')
          ->where('fk_documento = :iddocumento')
          ->andWhere('fk_tarea <> :idtarea')
          ->setParameter(':iddocumento', $Documento -> iddocumento,Type::getType('integer'))
          ->setParameter(':idtarea', $this -> Tarea -> idtarea,Type::getType('integer'))
          ->execute()->fetchAll();
          
      foreach($tareasDocumento as $key){
        $Tarea = new Tarea($key['fk_tarea']);      
        if($Tarea -> getState() != 1){//Si el estado no esta finalizado
          $validEstadoTareas = false;
        }
      }
      //Fin busqueda de tareas
      
      if($estadoTarea == 3){//Si la tarea ya recibio un avance (Proceso)
        $Ft -> estado_ticket = 4;
        $Ft -> update();
      } else if($estadoTarea == 1 && $validEstadoTareas){//Si la tarea ya la finalizaron por medio de un avance (Realizada) y todas las tareas estan finalizadas
        $Ft -> estado_ticket = 5;
        $Ft -> update();
      }
    }
  }
}
