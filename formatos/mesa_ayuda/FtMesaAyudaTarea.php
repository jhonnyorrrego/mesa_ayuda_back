<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

use Saia\core\model\Model;
use Saia\models\documento\Documento;
use Saia\models\tarea\Tarea;
use Saia\MesaAyuda\formatos\mesa_ayuda\UtilitiesFtMesaAyuda;
use Saia\models\tarea\IExternalEventsTask;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;

class FtMesaAyudaTarea implements IExternalEventsTask
{  
  protected $Tarea;
  protected $Instance;
  
  public function __construct(Model $Instance, Tarea $Tarea)
  {
      $this->Tarea = $Tarea;
      $this->Instance = $Instance;
  }  
  
  public function afterCreateTarea(): bool
  {
    return true;
  }
  public function afterUpdateTarea(): bool
  {
    if (!$this->Tarea->estado) {
        if ($DocumentoTarea = $this->Tarea->getDocument()) {
            $this->actualizarEstadoTareaTicket($DocumentoTarea->Documento);
        }
    }
    
    return true;
  }
  public function afterDeleteTarea(): bool
  {
    if ($DocumentoTarea = $this->Tarea->getDocument()) {
        $this->actualizarEstadoTareaTicket($DocumentoTarea->Documento);
    }
    
    return true;
  }
  public function afterCreateTareaAnexo(): bool
  {
    return true;
  }
  public function afterCreateTareaComentario(): bool
  {
    return true;
  }
  public function afterCreateTareaEstado(): bool
  {    
    if ($DocumentoTarea = $this -> Tarea -> getDocument()) {
        $this -> actualizarEstadoTareaTicket($DocumentoTarea->Documento);
    }
    
    return true;
  }
  public function afterCreateTareaEtiqueta(): bool
  {
    return true;
  }
  public function afterCreateTareaFuncionario(): bool
  {
    return true;
  }
  public function afterUpdateTareaFuncionario(): bool
  {
    return true;
  }
  public function afterCreateTareaNotificacion(): bool
  {
    return true;
  }
  public function afterCreateTareaPrioridad(): bool
  {
    return true;
  }
  public function afterCreateDocumentoTarea(): bool
  {
    $this->actualizarEstadoTareaTicket($this->Instance->Documento, FtMesaAyuda::ESTADO_PROCESO);
    
    return true;
  }
  
  public function actualizarEstadoTareaTicket(Documento $Documento, ?string $estado = null)
  {
      $estado = $estado ?? FtMesaAyuda::ESTADO_PENDIENTE;

      $data = UtilitiesFtMesaAyuda::getFinishTotalTask($Documento);
      if ($data['total']) {
          $estado = $data['total'] == $data['finish'] ?
              FtMesaAyuda::ESTADO_TERMINADO : FtMesaAyuda::ESTADO_PROCESO;
      }
      $Ft = $Documento->getFt();
      $estadoActual = $Ft -> estado_ticket;

      if ($estadoActual != $estado) {
          $Ft -> estado_ticket = $estado;
          $Ft->update();
      }
      
      return true;
  }
}
