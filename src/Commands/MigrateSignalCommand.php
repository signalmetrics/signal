<?php

namespace Signalmetrics\Signal\Commands;

use Illuminate\Console\Command;

class MigrateSignalCommand extends Command {

    public $signature = 'signal:migrate';

    public $description = 'Migrate Signal';

    public function handle(): int
    {
        $this->comment('Doing an initial migration...');
        $packageMigrationPath = 'vendor/signalmetrics/signal/database/migrations';

        passthru('php artisan migrate --database=signal --path=' . $packageMigrationPath);

        return self::SUCCESS;
    }


}
