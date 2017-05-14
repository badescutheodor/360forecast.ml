<?php

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->schema->create('settings', function(Blueprint $table) {
            $table->increments('id');
            $table->string('identifier');
            $table->string('key');
            $table->string('value');
            $table->index(['identifier', 'key']);
            $table->timestamps();
        });
    }

    public function down() {
        $this->schema->drop('settings');
    }
}
