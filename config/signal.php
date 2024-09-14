<?php

return [

    'signal_db' => [
        'driver' => 'sqlite',
        'database' => env('SIGNAL_DB_DATABASE', database_path('signal.sqlite')),
        'journal_mode' => 'wal',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'url' => '',
    ],


    'tables' => [
        'events' => 'events',
        'ip' => 'ip_addresses',
        'blacklist' => 'blacklist'
    ],

    /*
    |--------------------------------------------------------------------------
    | Spam Threshold
    |--------------------------------------------------------------------------
    |
    | If there are more than this many visits in a day, it's considered spam.
    |
    */
    'spam_threshold' => env('SIGNAL_SPAM_THRESHOLD', 1000)
];