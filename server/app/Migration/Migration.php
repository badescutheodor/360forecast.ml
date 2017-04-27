<?php

namespace App\Migration;

use Illuminate\Database\Capsule\Manager as Capsule;
use Phinx\Migration\AbstractMigration;

class Migration extends AbstractMigration
{
    /**
     * @var \Illuminate\Database\Capsule\Manager $capsule
     */
    public $capsule;

    /**
     * @var \Illuminate\Database\Schema\Builder $capsule
     */
    public $schema;

    /**
     * Eloquent initialization method
     */
    public function init() {
        $this->capsule = new Capsule;
        $this->capsule->addConnection([
            'driver' => 'sqlite',
            'database' => '../../storage/database/database.sqlite'
        ]);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();

        $this->schema = $this->capsule->schema();
    }
}