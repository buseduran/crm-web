<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google Gemini API Configuration
    |--------------------------------------------------------------------------
    |
    | This configuration file contains settings for the Google Gemini API
    | integration used for AI-powered report generation.
    |
    */

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
        'model' => env('GEMINI_MODEL', 'gemini-2.5-flash'),
        'base_url' => 'https://generativelanguage.googleapis.com/v1beta',
        'cache_ttl' => (int) env('GEMINI_CACHE_TTL', 86400), // 24 hours in seconds (1 day)
        'timeout' => (int) env('GEMINI_TIMEOUT', 30), // seconds
    ],

];
