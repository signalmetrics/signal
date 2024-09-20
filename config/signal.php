<?php

return [

    'signal_db' => [
        'driver' => 'sqlite',
        'database' => env('SIGNAL_DB_DATABASE', database_path('signal/signal.sqlite')),
        'journal_mode' => 'wal',
        'prefix' => '',
        'foreign_key_constraints' => true,
        'url' => '',
    ],


    'tables' => [
        'events' => 'events',
        'ip' => 'ip_addresses',
        'blacklist' => 'blacklist',
        'today' => 'today'
    ],

    /*
    |--------------------------------------------------------------------------
    | Privacy Mode
    |--------------------------------------------------------------------------
    |
    | By default, Signal honors the privacy intrinsically due all free people
    | and the legal privacy requirements dictated by GDPR.
    |
    */
    'privacy_mode' => env('SIGNAL_PRIVACY_MODE', false),

    /*
    |--------------------------------------------------------------------------
    | Throttle
    |--------------------------------------------------------------------------
    |
    | Laravel provides rate-limiting, which defaults to 60 requests per minute.
    | In some cases, your users may be likely to exceed that, especially
    | if you are sending a lot of custom events.
    |
    */
    'max_attempts_per_minute' => env('SIGNAL_MAX_ATTEMPTS_PER_MINUTE', 60),

    /*
    |--------------------------------------------------------------------------
    | Track Logged In Users
    |--------------------------------------------------------------------------
    |
    | By default, Signal honors the privacy intrinsically due all free people
    | and the legal privacy requirements dictated by GDPR.
    |
    */
    'track_logged_in_users' => env('SIGNAL_TRACK_LOGGED_IN_USERS', true),

    /*
    |--------------------------------------------------------------------------
    | Track in Debug Mode
    |--------------------------------------------------------------------------
    |
    | Usually people don't want to use Signal outside of production.
    |
    */
    'track_in_debug_mode' => env('SIGNAL_TRACK_IN_DEBUG_MODE', false),

     /*
    |--------------------------------------------------------------------------
    | Data Movement Frequency
    |--------------------------------------------------------------------------
    |
    | We temporarily store data in the "today" table until it's ready to
    | move into the "events" table. This is because "events" has a lot
    | of indexes, which can slow down writing a lot.
    |
    */
    'data_movement_frequency' => env('SIGNAL_DATA_MOVEMENT_FREQUENCY', 'everyThirtyMinutes'),

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