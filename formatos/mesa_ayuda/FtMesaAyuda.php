<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

class FtMesaAyuda extends FtMesaAyudaProperties
{
    const ESTADO_POR_CLASIFICAR = 1;
    const ESTADO_PENDIENTE = 2;
    const ESTADO_PROCESO = 3;
    const ESTADO_TERMINADO = 4;
    
    public $estados = array(0 => 'Pendiente por clasificar', 1 => 'Pendiente por clasificar', 2 => 'Pendiente', 3 => 'Proceso', 4 => 'Finalizado');
    
    public function __construct($id = null)
    {
        parent::__construct($id);
    }
    
    public function getEstadoTicket(){      
        $estadoAlmacenado = $this -> estado_ticket;
        if(!$estadoAlmacenado){
          $estadoAlmacenado = 0;
        }
        //$estado = '<div class="badge badge-important" style="">' . $this -> estados [$estadoAlmacenado] . '</div>';
        $estado = $this -> estados [$estadoAlmacenado];
        
        return($estado);
    }
    
    public function actualizarEstadoTarea($idTarea){
      if($idTarea){
        $this -> estado_ticket = $this -> ESTADO_PENDIENTE;//Tarea asignada
        if($this -> save()){
          
        }
      }
    }
}