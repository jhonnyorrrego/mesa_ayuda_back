<?php

namespace Saia\MesaAyuda\formatos\mesa_ayuda;

use Saia\core\model\ModelFormat;

class FtMesaAyudaProperties extends ModelFormat
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    protected function defaultDbAttributes()
    {
        return [
            'safe' => [
                'anexos',
				'clasificacion',
				'dependencia',
				'descripcion',
				'documento_iddocumento',
				'encabezado',
				'estado_ticket',
				'firma',
				'idft_mesa_ayuda',
				'pre_clasificacion',
				'pre_clasificacion_json',
				'responsable' 
            ],
            'date' => [],
            'table' => 'ft_mesa_ayuda',
            'primary' => 'idft_mesa_ayuda'
        ];
    }

    protected function defineMoreAttributes()
    {
        return [];
    }
}