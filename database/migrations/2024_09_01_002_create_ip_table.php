<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $connection = 'signal';

    protected function tableName(): string
    {
        return config('signal.tables.ip', 'ip_addresses');
    }

    public function up()
    {
        // If they've already migrated under the previous migration name, just skip
        if (Schema::hasTable($this->tableName())) {
            return;
        }

        Schema::create($this->tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('ip')->index()->unique();
            $table->integer('visits')->default(0);
            $table->dateTime('blacklist_at')->nullable();
            $table->dateTime('blacklist_expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists($this->tableName());
    }

};
