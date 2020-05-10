<?php

declare(strict_types=1);

namespace Saia\mesa_ayuda\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200426235150 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Llenado de informacion en la tabla de configuracio';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this -> insertConfig();
    }
    
    public function insertConfig()
    {
        $data = [
            'idma_clasificacion' => '1',
            'nombre' => "Sistemas",
            'cod_padre' => '0',
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        $idSistemas = $this->connection->lastInsertId();
        
        $data = [
            'idma_clasificacion' => '2',
            'nombre' => "Soporte y calidad",
            'cod_padre' => '0',
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        $idSoporte = $this->connection->lastInsertId();
        
        $data = [
            'idma_clasificacion' => '3',
            'nombre' => "Gestión humana",
            'cod_padre' => '0',
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        $idGestion = $this->connection->lastInsertId();
        
        $data = [
            'idma_clasificacion' => '4',
            'nombre' => "Planeación",
            'cod_padre' => '0',
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        $idPlaneacion = $this->connection->lastInsertId();
        
        $data = [
            'idma_clasificacion' => '5',
            'nombre' => "Proyectos",
            'cod_padre' => '0',
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        $idProyectos = $this->connection->lastInsertId();
        
        $data = [
            'idma_clasificacion' => '6',
            'nombre' => "Chat",
            'cod_padre' => $idSistemas,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '7',
            'nombre' => "Administrador de SAIA",
            'cod_padre' => $idSistemas,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '8',
            'nombre' => "Gmail",
            'cod_padre' => $idSistemas,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '10',
            'nombre' => "LOGIN EMAIL",
            'cod_padre' => $idSistemas,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '12',
            'nombre' => "Revisión",
            'cod_padre' => $idSoporte,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '13',
            'nombre' => "Revisiones generales",
            'cod_padre' => $idSoporte,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '14',
            'nombre' => "Historias laborales",
            'cod_padre' => $idGestion,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        
        $data = [
            'idma_clasificacion' => '15',
            'nombre' => "Otro",
            'cod_padre' => '0',
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
        $idOtro = $this->connection->lastInsertId();
        
        $data = [
            'idma_clasificacion' => '16',
            'nombre' => "Otro",
            'cod_padre' => $idOtro,
            'estado' => '1',
            'cant_dias' => '5',
            'responsables' => NULL,
            'responsables_json' => NULL
        ];
        
        $this->connection->insert('ma_clasificacion', $data);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("TRUNCATE TABLE ma_clasificacion");
    }
}
