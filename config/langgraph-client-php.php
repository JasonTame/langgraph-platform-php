<?php

// config for JasonTame/LangGraphClient
return [

    /*
    |--------------------------------------------------------------------------
    | LangGraph Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the LangGraph Client PHP SDK
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Your LangGraph Platform API key. For local development, you can use
    | 'fake-api-key' when running against a local LangGraph Server instance.
    |
    */
    'api_key' => env('LANGGRAPH_API_KEY', 'fake-api-key'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the LangGraph Platform API. For production, this should
    | be 'https://api.langchain.com'. For local development, use
    | 'http://localhost:8124' when running LangGraph Server locally.
    |
    */
    'base_url' => env('LANGGRAPH_BASE_URL', 'https://api.langchain.com'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait for a response before timing out.
    |
    */
    'timeout' => (int) env('LANGGRAPH_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Connect Timeout
    |--------------------------------------------------------------------------
    |
    | The number of seconds to wait while trying to connect to the server.
    |
    */
    'connect_timeout' => (int) env('LANGGRAPH_CONNECT_TIMEOUT', 5),

    /*
    |--------------------------------------------------------------------------
    | Retry Attempts
    |--------------------------------------------------------------------------
    |
    | The number of times to retry a failed request before giving up.
    |
    */
    'retries' => (int) env('LANGGRAPH_RETRIES', 3),

    /*
    |--------------------------------------------------------------------------
    | SSL Verification
    |--------------------------------------------------------------------------
    |
    | Whether to verify SSL certificates. Should be true in production.
    | Can be set to false for local development with self-signed certificates.
    |
    */
    'verify_ssl' => (bool) env('LANGGRAPH_VERIFY_SSL', true),

];
