<?php

use \App\Migration\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSearchTable extends Migration
{
    public function up()
    {
        $this->schema->create('searches', function(Blueprint $table) {
            $table->increments('id');
            $table->string('identifier');
            $table->string('text');
            $table->timestamps();
        });
    }

    public function down() {
        $this->schema->drop('searches');
    }
}
