<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Signalmetrics\Signal\Drawer\EventSchema;

return new class extends Migration {

    protected $connection = 'signal';

    protected function tableName(): string
    {
        return config('signal.tables.today', 'today');
    }

    public function up()
    {
        // If they've already migrated under the previous migration name, just skip
        if (Schema::hasTable($this->tableName())) {
            return;
        }

        Schema::create($this->tableName(), EventSchema::get());
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName());
    }

};
