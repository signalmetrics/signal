<?php

namespace Signalmetrics\Signal\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Signalmetrics\Signal\Models\SignalEvent;

class SignalEventFactory extends Factory {

    protected $model = SignalEvent::class;

    /**
     * This creates up to the total number of integers specified for hashing a certain number of users.
     */
    protected function randomXXH(int $max_count = 100)
    {
        $int = random_int(1, $max_count);
        return hash('xxh128', $int);
    }

    protected function xxh(string $str): string
    {
        return hash('xxh128', $str);
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

    public function definition(): array
    {
        $user_hash = $this->randomXXH();
        $random_page_visited = $this->randomPageList($user_hash);


        return [
            'visit_signature' => SignalEvent::generateRandomVisitSignature()['signature'],
            'type' => 'page_view',
            'custom_user_id' => $this->faker->randomElement([null, $this->faker->randomNumber()]),
            'title' => $random_page_visited['title'],
            'user_agent' => null, // right now we're just not filling it out.
            'ip' => null, // right now we're just not filling it out.
            'user_hash' => $user_hash,
            'page_view_hash' => $random_page_visited['page_view_hash'],
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

            'created_at' => SignalEvent::generateRandomVisitSignature()['date'],
            'updated_at' => SignalEvent::generateRandomVisitSignature()['date'],
        ];
    }

}
