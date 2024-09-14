<?php

namespace Signalmetrics\Signal\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PDO;

class InstallCommand extends Command {

    public $signature = 'signal:install';

    public $description = 'Install Signal';


    public function handle(): int
    {

        $this->intro();

        $this->publishVendorFiles();

        $this->setupDatabase();

        $this->migrate();

        $this->addToGitIgnore();

        $this->info('<fg=white;bg=green>Success!</> Signal is installed...make something great!');
        $this->info('Start using it at ' . config('app.url') . '/analytics');
//        $this->info('And don\'t forget to run `npm install && npm run dev` if you haven\'t already.');

        return self::SUCCESS;
    }

    protected function intro()
    {
        $this->slowInfo('Thanks for trying out');
        $this->slowInfo('↓↓↓↓↓');
        $this->slowInfo('↓↓↓');
        $this->slowInfo('↓');
        $this->slowInfo('');
        $this->slowInfo(' Signal', 'warn');
        $this->slowInfo('');

        $this->slowInfo('     ╬═╬     ');
        $this->slowInfo('     ╬═╬     ');
        $this->slowInfo('     ╬═╬  ☻/ ');
        $this->slowInfo('     ╬═╬ /▌  ');
        $this->slowInfo('    ╬═╬ / \\');
        $this->slowInfo('');
        $this->slowInfo('The free, self hosted');
        $this->slowInfo('analytics platform');
        $this->slowInfo('for Laravel apps.');
        $this->slowInfo('');
        $this->slowInfo('');

        sleep(0.5);
        $this->slowInfo('');
        $this->slowInfo('Installation starts in...');
        $this->slowInfo('');
        $this->slowInfo('3');
        $this->slowInfo('2');
        $this->slowInfo('1');
        sleep(1);
    }

    public function slowInfo($text, $type = 'info')
    {
        // center the text.
        $length = str($text)->length();
        $padding = round((32 - $length) / 2, 0);

        usleep(220000);

        $this->$type('>>>>' . str_repeat(' ', $padding) . $text . str_repeat(' ', $padding));
    }

    protected function getStubPath(string $subpath)
    {
        return __DIR__ . '/..' . $subpath;
    }

    protected function publishVendorFiles(): void
    {
        $this->comment('Publishing assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'signal-assets']);

        $this->comment('Publishing migrations...');
        $this->callSilent('vendor:publish', ['--tag' => 'signal-migrations']);

        $this->comment('Publishing config file...');
        $this->callSilent('vendor:publish', ['--tag' => 'signal-config']);
    }


    protected function addToGitIgnore(): void
    {
        $this->info('Updating gitignore...');
        $ignore_file = base_path('.gitignore');
        file_put_contents(
            $ignore_file,
            PHP_EOL . '/database/signal.sqlite',
            FILE_APPEND
        );
    }

    protected function setupDatabase(): void
    {

        $db_file = config('signal.signal_db.database');

        if (file_exists($db_file)) {
            $this->comment("Database already exists. Skipping database setup.");
            return;
        }

        $this->comment('Making database...');

        File::put($db_file, '');

        // Connect to the SQLite database and execute the PRAGMA command
        $pdo = new PDO('sqlite:' . $db_file);
        $pdo->exec('PRAGMA journal_mode = WAL;');

        // Disable synchronous mode
        $pdo->exec('PRAGMA synchronous = NORMAL;');

        $this->comment('WAL mode enabled and Synchronous mode disabled for Signal.');

        // reloads the .env file
//            $this->line('Reloading .env file...');
//            $this->call('config:cache');
//            $this->call('config:clear');
    }

    /**
     * This runs migrations specifically for
     */
    protected function migrate(): void
    {
        (new MigrateSignalCommand())->handle();

    }

}
