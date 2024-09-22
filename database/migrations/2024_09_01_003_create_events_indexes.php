<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    protected $connection = 'signal';

    protected function tableName(): string
    {
        return config('signal.tables.events', 'events');
    }

    public function up()
    {

        Schema::table($this->tableName(), function (Blueprint $table) {


             DB::statement('CREATE INDEX path_name_created_at ON events(path_name,created_at)');

             DB::statement('CREATE INDEX referrer_created_at ON events(referrer,created_at) WHERE referrer IS NOT NULL');
             DB::statement('CREATE INDEX browser_created_at ON events(browser,created_at) WHERE browser IS NOT NULL');
             DB::statement('CREATE INDEX country_code_created_at ON events(country_code,created_at) WHERE country_code IS NOT NULL');
             DB::statement('CREATE INDEX device_type_created_at ON events(device_type,created_at) WHERE device_type IS NOT NULL');

//             DB::statement('CREATE INDEX platform_created_at ON events(platform,created_at) WHERE platform IS NOT NULL');

             // User indices
//             DB::statement('CREATE INDEX custom_user_id_created_at ON events(custom_user_id,created_at) WHERE custom_user_id IS NOT NULL');
//             DB::statement('CREATE INDEX user_hash_created_at ON events(user_hash,created_at)');
        });

    }

    public function down()
    {
        Schema::table($this->tableName(), function (Blueprint $table) {
            $table->dropIndex([
                'custom_user_id_created_at',
                'user_hash_created_at',
                'referrer_created_at',
                'country_code_created_at',
                'device_type_created_at',
                'browser_created_at',
                'platform_created_at'
            ]);
        });
    }

};
