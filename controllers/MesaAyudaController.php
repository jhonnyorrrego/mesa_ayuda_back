<?php

use \Doctrine\DBAL\Types\Type;

class MesaAyudaController
{
  /**
     * datos que llegan por el request
     *
     * @var array
     * @author Andres Agudelo <andres.agudelo@cerok.com>
     * @date 2019
     */
    protected $request;

    /**
     *
     * @var Expediente
     * @author Andres Agudelo <andres.agudelo@cerok.com>
     * @date 2019
     */
    public $FtMesaAyuda;

    public function __construct(int $id, array $request = null)
    {
        $this->request = $request;
        $this->FtMesaAyuda = new FtMesaAyuda($id);
    }
    
    
}
