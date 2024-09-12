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
        'events' => 'signal_events'
    ]
];