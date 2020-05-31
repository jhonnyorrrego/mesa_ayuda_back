<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

use Doctrine\DBAL\Types\Type;
use Saia\core\DatabaseConnection;

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
}