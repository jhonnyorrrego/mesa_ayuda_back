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
  
  public function afterCreateTarea(): void
  {
    
  }
  public function afterUpdateTarea(): void
  {
    if (!$this->Tarea->estado) {
        if ($DocumentoTarea = $this->Tarea->getDocument()) {
            $this->actualizarEstadoTareaTicket($DocumentoTarea->Documento);
        }
    }
  }
  public function afterDeleteTarea(): void
  {
    if ($DocumentoTarea = $this->Tarea->getDocument()) {
        $this->actualizarEstadoTareaTicket($DocumentoTarea->Documento);
    }
  }
  public function afterCreateTareaAnexo(): void
  {
    
  }
  public function afterCreateTareaComentario(): void
  {
  }
  public function afterCreateTareaEstado(): void
  {    
    if ($DocumentoTarea = $this -> Tarea -> getDocument()) {
        $this -> actualizarEstadoTareaTicket($DocumentoTarea->Documento);
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
    $this->actualizarEstadoTareaTicket($this->Instance->Documento, FtMesaAyuda::ESTADO_PROCESO);
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
  }
}
