<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $connection = 'signal';

    protected function tableName(): string
    {
        return config('signal.tables.events', 'signal_events');
    }

    public function up()
    {
        // If they've already migrated under the previous migration name, just skip
        if (Schema::hasTable($this->tableName())) {
            return;
        }

        Schema::create($this->tableName(), function (Blueprint $table) {
            $table->snowflakeId();

            $table->string('type')->index();
            $table->json('data')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName());
    }

};
