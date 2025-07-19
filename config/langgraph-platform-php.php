<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LangGraph Platform API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration settings for the LangGraph Platform API client.
    | These settings control how the SDK communicates with the LangGraph API.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | Your LangGraph Platform API key. For local development, you can use
    | 'fake-api-key'. For production, this should be a valid API key from
    | your LangGraph Platform account.
    |
    */
    'api_key' => env('LANGGRAPH_API_KEY', 'fake-api-key'),

    /*
    |--------------------------------------------------------------------------
    | Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for the LangGraph Platform API. Defaults to the public
    | LangGraph Platform API, but can be customized for self-hosted instances.
    |
    */
    'base_url' => env('LANGGRAPH_BASE_URL', 'https://api.langchain.com'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for the HTTP client used to communicate with
    | the LangGraph Platform API.
    |
    */
    'timeout' => env('LANGGRAPH_TIMEOUT', 30),
    'connect_timeout' => env('LANGGRAPH_CONNECT_TIMEOUT', 5),
    'retries' => env('LANGGRAPH_RETRIES', 3),

    /*
    |--------------------------------------------------------------------------
    | SSL Verification
    |--------------------------------------------------------------------------
    |
    | Whether to verify SSL certificates when making API requests.
    | Should be true in production for security.
    |
    */
    'verify_ssl' => env('LANGGRAPH_VERIFY_SSL', true),

    /*
    |--------------------------------------------------------------------------
    | Default Headers
    |--------------------------------------------------------------------------
    |
    | Additional headers to include with all API requests.
    |
    */
    'default_headers' => [
        'User-Agent' => 'langgraph-platform-php/1.0',
        'Accept' => 'application/json',
        'Content-Type' => 'application/json',
    ],

    /*
    |--------------------------------------------------------------------------
    | Streaming Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Server-Sent Events (SSE) streaming support.
    |
    */
    'streaming' => [
        'enabled' => env('LANGGRAPH_STREAMING_ENABLED', true),
        'chunk_size' => env('LANGGRAPH_STREAMING_CHUNK_SIZE', 1024),
        'timeout' => env('LANGGRAPH_STREAMING_TIMEOUT', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Webhook Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for webhook support in async operations.
    |
    */
    'webhooks' => [
        'enabled' => env('LANGGRAPH_WEBHOOKS_ENABLED', true),
        'max_url_length' => env('LANGGRAPH_WEBHOOK_MAX_URL_LENGTH', 65536),
    ],
];
