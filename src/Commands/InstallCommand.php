<?php

namespace Signalmetrics\Signal\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PDO;

class InstallCommand extends Command {

    public $signature = 'signal:install {--fast : Skip the intro}';

    public $description = 'Install Signal';


    public function handle(): int
    {

        if (!$this->option('fast')) {
            $this->intro();
        }

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

        $this->slowInfo('↓↓↓↓↓');
        $this->slowInfo('↓↓↓');
        $this->slowInfo('↓');
        $this->slowInfo('');

        $this->slowInfo('Thanks for using Signal!');
        $this->slowInfo('---');
        $this->slowInfo('The free');
        $this->slowInfo('analytics tracker');
        $this->slowInfo('for Laravel apps.');

        $this->slowInfo('');
        $this->slowInfo('');
        sleep(2);

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
            PHP_EOL . '/database/signal/signal.sqlite',
            FILE_APPEND
        );
    }

    protected function setupDatabase(): void
    {
        $db_file = config('signal.signal_db.database');

        // Get the directory path (without the filename)
        $db_directory = dirname($db_file);

        // Check if the directory exists
        if (!File::exists($db_directory)) {
            $this->comment('Creating database/signal directory...');

            // Create the directory with appropriate permissions
            File::makeDirectory($db_directory, 0755, true);
        }

        if (file_exists($db_file)) {
            $this->comment("Signal database already exists.");
            return;
        }

        $this->comment('Making a SQLite database to store analytics data...');

        // Create the SQLite database file
        File::put($db_file, '');

        // Connect to the SQLite database and execute the PRAGMA command
        $pdo = new PDO('sqlite:' . $db_file);
        $pdo->exec('PRAGMA journal_mode = WAL;');

        // Disable synchronous mode
        $pdo->exec('PRAGMA synchronous = NORMAL;');

        $this->comment('WAL mode enabled and Synchronous mode disabled for Signal.');
    }

    /**
     * This runs migrations for Signal.
     */
    protected function migrate(): void
    {
        $this->call('signal:migrate');
    }

}
