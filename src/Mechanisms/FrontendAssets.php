<?php

namespace Signalmetrics\Signal\Mechanisms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Signalmetrics\Signal\Drawer\Utils;

class FrontendAssets {

    public $hasRenderedScripts = false;
    public $scriptTagAttributes = [];

    function register()
    {
        app()->instance(static::class, $this);
    }

    public function boot()
    {

        // Register the signal.js route
        app($this::class)->setScriptRoute(function ($handle) {
            return config('app.debug')
                ? Route::get('/signal/signal.js', $handle)
                : Route::get('/signal/signal.min.js', $handle);
        });

        // Register the route for source maps
        Route::get('/signal/signal.min.js.map', [static::class, 'maps']);

        // Register Blade directives for signal scripts
        Blade::directive('signal', [static::class, 'signalScripts']);

    }

    public function setScriptRoute($callback)
    {
        $route = $callback([self::class, 'returnJavaScriptAsFile']);
        $this->javaScriptRoute = $route;
    }

    public static function signalScripts($expression = null)
    {
        return '{!! \Signalmetrics\Signal\Mechanisms\FrontendAssets::scripts(' . $expression . ') !!}';
    }

    public function returnJavaScriptAsFile()
    {
        $path = config('app.debug')
            ? __DIR__ . '/../../dist/signal.js'
            : __DIR__ . '/../../dist/signal.min.js';

        // Debugging: dump the file path
        dump($path);

        return Utils::pretendResponseIsFile($path);
    }

    public function maps()
    {
        return Utils::pretendResponseIsFile(__DIR__ . '/../../../dist/signal.min.js.map');
    }

    public static function scripts($options = [])
    {
        app(static::class)->hasRenderedScripts = true;

        $debug = config('app.debug');

        if($debug && ! config('signal.track_in_debug_mode')) {
            return "<!-- Signal is disabled. --> ";
        }

        $scripts = static::js($options);


        $html = $debug ? ['<!-- Signal Scripts -->'] : [];
        $html[] = $scripts;

        return implode("\n", $html);
    }

    public static function js($options)
    {
        // Use the default route
        $url = app(static::class)->javaScriptRoute->uri;

        // Optionally use asset_url from config
        $url = config('signal.asset_url') ?: $url;

        // Legacy or custom asset URL options
        $url = $options['asset_url'] ?? $url;
        $url = $options['url'] ?? $url;

        // Ensure the URL is properly formatted
        $url = rtrim($url, '/');
        $url = (string) str($url)->when(!str($url)->isUrl(), fn($url) => $url->start('/'));

        // Cache-busting in development by adding a timestamp to the script URL
        if (config('app.debug')) {
            $timestamp = time(); // Alternatively, you can use file modification time with filemtime()
            $url .= "?v={$timestamp}";
        }

        // Dynamically generate data attributes from options
        $attributes = '';

        /**
         * By default, if we have a logged in user we
         * track them using their ID.
         */
        if (config('signal.track_logged_in_users') && auth()->user()) {
            $id = auth()->user()->id;
            $attributes .= " data-custom-user-id={$id}";
        }

        foreach ($options as $key => $value) {
            $attributes .= ' data-' . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
        }

        $token = app()->has('session.store') ? csrf_token() : '';

        $nonce = isset($options['nonce']) ? "nonce=\"{$options['nonce']}\"" : '';
        $extraAttributes = Utils::stringifyHtmlAttributes(app(static::class)->scriptTagAttributes);

        return <<<HTML
        <script src="{$url}" defer {$nonce} data-csrf="{$token}" {$attributes} {$extraAttributes}></script>
        HTML;
    }

    protected static function minify($subject)
    {
        return preg_replace('~(\v|\t|\s{2,})~m', '', $subject);
    }

}
