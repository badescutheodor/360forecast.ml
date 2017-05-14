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
            'driver'    => 'mysql',
            'host'      => getenv('DATABASE_HOST') ? getenv('DATABASE_HOST') : '127.0.0.1',
            'port'      => getenv('DATABASE_PORT') ? getenv('DATABASE_PORT') : 3306,
            'database'  => getenv('DATABASE_NAME') ? getenv('DATABASE_NAME') : '360forecast.ml',
            'username'  => getenv('DATABASE_USER') ? getenv('DATABASE_USER') : 'root',
            'password'  => getenv('DATABASE_PASSWORD') ? getenv('DATABASE_PORT') : '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
        ]);
        $this->capsule->bootEloquent();
        $this->capsule->setAsGlobal();

        $this->schema = $this->capsule->schema();
    }
}