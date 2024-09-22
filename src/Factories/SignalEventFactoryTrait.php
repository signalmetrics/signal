<?php

namespace Signalmetrics\Signal\Factories;

trait SignalEventFactoryTrait {

    /**
     * This creates up to the total number of integers specified for hashing a certain number of users.
     */
    protected function randomXXH(int $max_count = 100)
    {
        $int = random_int(1, $max_count);
        return hash('xxh64', $int);
    }

    protected function xxh(string $str): string
    {
        return hash('xxh64', $str);
    }

    protected function randomPageList(string $user_hash): array
    {
        return $this->faker->randomElement([
            ['title' => 'About', 'path_name' => '/about', 'page_view_hash' => $this->xxh($user_hash . '/about')],
            ['title' => 'Home', 'path_name' => '/', 'page_view_hash' => $this->xxh($user_hash . '/')],
            ['title' => 'Contact', 'path_name' => '/contact', 'page_view_hash' => $this->xxh($user_hash . '/contact')],
            ['title' => 'Product Page', 'path_name' => '/product-page', 'page_view_hash' => $this->xxh($user_hash . '/product-page')],
            ['title' => 'Two Time Emmy-Award Winning Actor Jim Carrey', 'path_name' => '/two-time-award-winning-actor-jim-carrey', 'page_view_hash' => $this->xxh($user_hash . '/two-time-award-winning-actor-jim-carrey')],
        ]);
    }

    /**
     * This is used for the factory to get a random visit signature.
     */
    public static function generateRandomVisitSignature($max_days = 90)
    {
        // Get a random point in time in the past 90
        $random_day_int = random_int(0, $max_days);
        $random_hour_int = random_int(0, 23);
        $random_minute = random_int(0, 59);
        $random_second = random_int(0, 59);
        $random_millisecond = random_int(0, 99);
        $date = now()
            ->subDay($random_day_int)
            ->subHours($random_hour_int)
            ->subMinutes($random_minute)
            ->subSeconds($random_second)
            ->subMilliseconds($random_millisecond);

        // Add a random number that's 5 digits.
        $random_int = random_int(10000, 99999);

        return ['date' => $date, 'signature' => $date->timestamp . $random_int];
    }

    public function definition(): array
    {
        $user_hash = $this->randomXXH();
        $random_page_visited = $this->randomPageList($user_hash);

        $random_date = $this->generateRandomVisitSignature();

        return [
            'visit_signature' => $random_date['signature'],
            'type' => 'page_view',
            'custom_user_id' => $this->faker->randomElement([null, $this->faker->randomNumber()]),
            'title' => $random_page_visited['title'],
            'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)', // right now we're just not filling it out.
            'ip' => '74.115.209.58', // right now we're just not filling it out.
            'user_hash' => $user_hash,
//            'page_view_hash' => $random_page_visited['page_view_hash'], // turned off because I'm not sure if we need it.
            'duration' => $this->faker->randomElement([null, $this->faker->numberBetween(0, 180)]),
            'referrer' => $this->faker->randomElement([null, 'google', 'facebook', null, null]),
            'host_name' => 'example.com',
            'path_name' => $random_page_visited['path_name'],
            'country_code' => $this->faker->randomElement([null, null, 'us', 'gb', 'mx']), // @TODO make sure these country codes are correct.
            'city' => $this->faker->randomElement([null, null, 'San Diego', 'Columbia', 'Fort Meyers']),
            'device_type' => null,
            'device_name' => $this->faker->randomElement([null, 'Macintosh', 'Windows', 'iOS', 'Android']),
            'platform' => $this->faker->randomElement([null, 'OSX', 'Windows', 'iOS', 'Android']), // @TODO some of these mappings are wrong
            'browser' => $this->faker->randomElement([null, 'Chrome', 'Firefox']), // @TODO some of these mappings are wrong
            'page_load_time_ms' => $this->faker->numberBetween(0, 1500),

            'created_at' => $random_date['date'],
            'updated_at' => $random_date['date'],

            // not part of the model, just used for testing.
            'dispatch_moment' => $random_date['date']->timestamp,
            'url' => 'https://example.com/about'
        ];
    }

}