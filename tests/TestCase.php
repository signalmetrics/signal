<?php

namespace Signalmetrics\Signal\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Signalmetrics\Signal\SignalServiceProvider;

class TestCase extends Orchestra {

    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn(string $modelName) => 'Signalmetrics\\Signal\\Database\\Factories\\' . class_basename($modelName) . 'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            SignalServiceProvider::class,
            LivewireServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
//        config()->set('database.default', 'testing');
        $app['config']->set('database.default', 'sqlite');

        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('app.key', 'base64:rmxs0rRwrBxi295PKtDNszWo6a+/dMayj8ExvT11ntw=');

        // We have to manually alias since
        // the composer extras alias isn't getting picked up.
//        $loader = AliasLoader::getInstance();
//        $loader->alias('Prodigy', \ProdigyPHP\Prodigy\Prodigy::class);


        /**
         * Migrations
         */
//        Schema::dropAllTables();
//
//        $migration = include __DIR__ . '/../database/migrations/2014_create_users_table.php';
//        $migration->up();
//
//        $migration = include __DIR__ . '/../database/migrations/2015_create_media_table.php';
//        $migration->up();
//
//        $migration = include __DIR__ . '/../database/migrations/2023_03_01_create_prodigy_tables.php.stub';
//        $migration->up();
    }

//    public function loginCorrectUser()
//    {
//        $this->actingAs(User::factory()->create(['name' => 'Stephen', 'email' => 'stephen@bate-man.com']));
//    }

}
