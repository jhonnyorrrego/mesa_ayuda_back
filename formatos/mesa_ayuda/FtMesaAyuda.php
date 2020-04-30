<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

class FtMesaAyuda extends FtMesaAyudaProperties
{
    public $estados = array(0 => 'Pendiente por clasificar', 1 => 'Pendiente por clasificar', 2 => 'Clasificado', 3 => 'Tarea asignada', 4 => 'Avance asignado', 5 => 'Finalizado');
    
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
        $this -> estado_ticket = 3;//Clasificado
        if($this -> save()){
          
        }
      }
    }
}