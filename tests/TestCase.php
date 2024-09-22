<?php

namespace Signalmetrics\Signal\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
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

        /**
         * We have to move the IP Service files into place for the test runner to find.
         */
        $to = base_path('vendor/signalmetrics/signal/ip_service');
        $from = __DIR__ . '/../ip_service';
        File::copyDirectory($from, $to);

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
        // Set the default connection to `signal`
        $app['config']->set('database.default', 'signal');

        // Set the `signal` connection to use SQLite in-memory database
        $app['config']->set('database.connections.signal', [
            'driver' => 'sqlite',
            'database' => ':memory:', // In-memory database
            'prefix' => '',
            'foreign_key_constraints' => true,
        ]);

        // Set the application key (needed for encryption, etc.)
        $app['config']->set('app.key', 'base64:rmxs0rRwrBxi295PKtDNszWo6a+/dMayj8ExvT11ntw=');

        // Optionally, you can also set other configurations like cache or queue
        $app['config']->set('cache.driver', 'array');
        $app['config']->set('queue.default', 'sync');

        Schema::dropAllTables();

        $database_dir = __DIR__ . '/../database/migrations';

        $migration = include $database_dir . '/2024_09_01_001_create_events_table.php';
        $migration->up();

        $migration = include $database_dir . '/2024_09_01_002_create_ip_table.php';
        $migration->up();

        $migration = include $database_dir . '/2024_09_01_002_create_today_table.php';
        $migration->up();

        $migration = include $database_dir . '/2024_09_01_003_create_events_indexes.php';
        $migration->up();

    }


}
