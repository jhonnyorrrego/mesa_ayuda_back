<?php

declare(strict_types=1);

namespace Saia\mesa_ayuda\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200507032342 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adicion campo descripcion, tipo de dias a la tabla de configuracion clasificacion';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this -> addColumnClasificacion($schema);
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $data = [
            'descripcion',
            'tipo_dias'
        ];

        foreach ($data as $table) {
            if ($schema->hasTable($table)) {
                $schema->dropTable($table);
            }
        }
    }
    
    public function addColumnClasificacion($schema){
        $table = $schema -> getTable('ma_clasificacion');
        
        $table -> addColumn('descripcion', 'string', [
            'notnull' => false
        ]);
        
        $table -> addColumn('tipo_dias', 'integer', [
            'notnull' => false
        ]);
    }
}
