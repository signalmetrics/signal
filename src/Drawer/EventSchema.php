<?php

namespace Signalmetrics\Signal\Drawer;

use Illuminate\Database\Schema\Blueprint;

class EventSchema {

    public static function get(): \Closure
    {
        return function (Blueprint $table) {
            // We were using snowflake, but essentially recreated it on the frontned with visit_signature.
            // $table->snowflakeId();

            // epochal milliseconds + random salt, for unique signature on visit.
            $table->unsignedBigInteger('visit_signature')->primary();

            // e.g., "page_view", "button_click"
            $table->string('type');

            // Host name ("example.com")
            $table->string('host_name')->nullable();

            // Path name ("/about")
            $table->string('path_name')->nullable();

            // For authenticated users in the system.
            $table->unsignedBigInteger('custom_user_id')->nullable();

            // Page title from browser
            $table->string('title')->nullable();

            // User agent string
            $table->string('user_agent')->nullable();

            // IP address
            $table->string('ip')->nullable();

            // Hashed identifier for users
            $table->string('user_hash')->nullable();

            // Hashed identifier for pageviews
//            $table->string('page_view_hash')->nullable();

            // Time on page (seconds)
            $table->integer('duration')->nullable();

            // Page referrer ("Google") || Note: null is considered direct
            $table->string('referrer')->nullable();

            // Determine if the page view is a unique user.
//            $table->boolean('is_unique')->nullable();

            // Determine if we have a crawler.
            $table->string('crawler')->nullable();

            // Size of the viewport and screen. e.g. x: 592, y: 1123
//            $table->json('viewport')->nullable();
//            $table->json('screen_resolution')->nullable();

//            $table->string('connection_type')->nullable(); // e.g., "4G"
            $table->string('language')->nullable(); // Language
            $table->string('country_code')->nullable(); // Country code
            $table->string('city')->nullable(); // City
            $table->string('device_type')->nullable(); // Device type (nullable)
            $table->string('device_name')->nullable(); // e.g., "Macintosh"
            $table->string('platform')->nullable(); // e.g., "OS X"
            $table->string('browser')->nullable(); // e.g., "Chrome" or "Firefox"
            $table->integer('page_load_time_ms')->nullable(); // Page load time in milliseconds

            $table->string('utm_source')->nullable(); // "google" "facebook"
            $table->string('utm_medium')->nullable(); // "email" "cpc"
            $table->string('utm_campaign')->nullable(); // "black+friday"
            $table->string('utm_term')->nullable(); // often paid search term
            $table->string('utm_content')->nullable(); // differentiates two different terms

            // Additional metadata can be stored here by the user.
            $table->json('data')->nullable();

            $table->timestamps();

        };
    }
}