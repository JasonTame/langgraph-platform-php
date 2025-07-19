# LangGraph Client for PHP

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jasontame/langgraph-client-php.svg?style=flat-square)](https://packagist.org/packages/jasontame/langgraph-client-php)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jasontame/langgraph-client-php/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jasontame/langgraph-client-php/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jasontame/langgraph-client-php/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jasontame/langgraph-client-php/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jasontame/langgraph-client-php.svg?style=flat-square)](https://packagist.org/packages/jasontame/langgraph-client-php)

An unofficial PHP client SDK for interacting with the [LangGraph Platform API](https://langchain-ai.github.io/langgraph/cloud/reference/api/api_ref.html). This package provides a clean, Laravel-friendly interface for building AI agents and workflows using LangGraph's powerful platform.

## Features

- ✅ **Complete API Coverage** - Assistants, Threads, Runs, Crons, and Store operations
- ✅ **Laravel Integration** - Service provider, facade, and configuration
- ✅ **Streaming Support** - Real-time Server-Sent Events (SSE) streaming
- ✅ **Webhook Support** - Async operations with webhook callbacks
- ✅ **Error Handling** - Comprehensive exception classes with detailed error information
- ✅ **HTTP Client** - Built on Guzzle with retry logic and timeout handling
- ✅ **Type Safety** - Full PHP 8.3+ type declarations

## Installation

Install the package via Composer:

```bash
composer require jasontame/langgraph-client-php
```

### Laravel Setup

Publish the configuration file:

```bash
php artisan vendor:publish --tag="langgraph-client-php-config"
```

Add your LangGraph Platform API credentials to your `.env` file:

```env
LANGGRAPH_API_KEY=your-api-key-here
LANGGRAPH_BASE_URL=https://api.langchain.com
```

For local development, you can use:
```env
LANGGRAPH_API_KEY=fake-api-key
LANGGRAPH_BASE_URL=http://localhost:8124
```

## Quick Start

### Using the Facade (Laravel)

```php
use JasonTame\LangGraphClient\Facades\LangGraphClient;

// Create an assistant
$assistant = LangGraphClient::assistants()->create([
    'graph_id' => 'my-graph',
    'name' => 'My Assistant',
    'config' => ['recursion_limit' => 10],
    'metadata' => ['version' => '1.0']
]);

// Create a thread
$thread = LangGraphClient::threads()->create([
    'metadata' => ['user_id' => 'user123']
]);

// Run the assistant
$run = LangGraphClient::runs()->create($thread['thread_id'], [
    'assistant_id' => $assistant['assistant_id'],
    'input' => ['message' => 'Hello, world!']
]);

echo "Run status: " . $run['status'];
```

### Using the Client Directly

```php
use JasonTame\LangGraphClient\LangGraphClient;

// Create client instance
$client = new LangGraphClient([
    'api_key' => 'your-api-key',
    'base_url' => 'https://api.langchain.com',
    'timeout' => 30,
    'retries' => 3
]);

// Or use environment variables
$client = LangGraphClient::fromEnvironment();
```

## Configuration

### Environment Variables

The SDK can be configured using environment variables:

- `LANGGRAPH_API_KEY`: Your LangGraph Platform API key (use `fake-api-key` for local development)
- `LANGGRAPH_BASE_URL`: Custom base URL (defaults to `https://api.langchain.com`)
- `LANGGRAPH_TIMEOUT`: Request timeout in seconds (default: 30)
- `LANGGRAPH_RETRIES`: Number of retry attempts (default: 3)

### Client Configuration

```php
$client = new LangGraphClient([
    'api_key' => 'your-api-key',
    'base_url' => 'https://custom-api.example.com',
    'timeout' => 60,
    'retries' => 5
]);

// Or configure after initialization
$client->configure([
    'timeout' => 60,
    'retries' => 5
]);
```

## Usage Examples

### Assistants

```php
// Create an assistant
$assistant = $client->assistants()->create([
    'graph_id' => 'my-graph',
    'name' => 'My Assistant',
    'config' => ['recursion_limit' => 10],
    'metadata' => ['version' => '1.0']
]);

// Find an assistant
$assistant = $client->assistants()->find('assistant-id');

// Search assistants
$assistants = $client->assistants()->search([
    'metadata' => ['version' => '1.0'],
    'limit' => 10
]);

// Update an assistant
$client->assistants()->update('assistant-id', [
    'name' => 'Updated Name'
]);

// Delete an assistant
$client->assistants()->delete('assistant-id');
```

### Threads

```php
// Create a thread
$thread = $client->threads()->create([
    'metadata' => ['user_id' => 'user123']
]);

// Get thread state
$state = $client->threads()->state($thread['thread_id']);

// Update thread state
$client->threads()->updateState($thread['thread_id'], [
    'values' => ['key' => 'value']
]);

// Get thread history
$history = $client->threads()->history($thread['thread_id'], [
    'limit' => 10
]);

// Search threads
$threads = $client->threads()->search([
    'status' => 'idle',
    'limit' => 10
]);
```

### Runs

```php
// Create a run
$run = $client->runs()->create($thread['thread_id'], [
    'assistant_id' => $assistant['assistant_id'],
    'input' => ['message' => 'Hello!']
]);

// Create a run with webhook
$run = $client->runs()->create($thread['thread_id'], [
    'assistant_id' => $assistant['assistant_id'],
    'input' => ['message' => 'Hello!'],
    'webhook' => 'https://your-app.com/webhooks/langgraph'
]);

// Stream a run
foreach ($client->runs()->stream($thread['thread_id'], [
    'assistant_id' => $assistant['assistant_id'],
    'input' => ['message' => 'Hello!']
]) as $event) {
    echo "Event: " . $event['type'] . "\n";
    print_r($event['data']);
}

// Wait for a run to complete
$result = $client->runs()->wait($thread['thread_id'], [
    'assistant_id' => $assistant['assistant_id'],
    'input' => ['message' => 'Hello!']
]);

// List runs
$runs = $client->runs()->list($thread['thread_id']);

// Cancel a run
$client->runs()->cancel($thread['thread_id'], $run['run_id']);
```

### Crons

**Note: Cron functionality requires LangGraph Platform Enterprise**

```php
// Create a cron job
$cron = $client->crons()->create([
    'assistant_id' => $assistant['assistant_id'],
    'schedule' => '0 */6 * * *', // Every 6 hours
    'payload' => ['task' => 'periodic_task']
]);

// List cron jobs
$crons = $client->crons()->list([
    'assistant_id' => $assistant['assistant_id']
]);

// Enable/disable cron jobs
$client->crons()->enable($cron['cron_id']);
$client->crons()->disable($cron['cron_id']);
```

### Store Operations

```php
// Store data
$client->store()->put('namespace', 'key', ['data' => 'value']);

// Retrieve data
$item = $client->store()->get('namespace', 'key');

// List items in namespace
$items = $client->store()->list('namespace', ['prefix' => 'user_']);

// Delete data
$client->store()->delete('namespace', 'key');

// Batch operations
$client->store()->batchPut([
    ['namespace' => 'ns1', 'key' => 'key1', 'value' => 'value1'],
    ['namespace' => 'ns1', 'key' => 'key2', 'value' => 'value2']
]);
```

## Streaming

The SDK supports Server-Sent Events (SSE) streaming for real-time responses:

```php
foreach ($client->runs()->stream($threadId, [
    'assistant_id' => $assistantId,
    'input' => ['message' => 'Tell me a story']
]) as $event) {
    switch ($event['type']) {
        case 'data':
            echo "Data: " . json_encode($event['data']) . "\n";
            break;
        case 'message':
            echo "Message: " . json_encode($event['data']) . "\n";
            break;
        case 'error':
            echo "Error: " . json_encode($event['data']) . "\n";
            break;
        case 'done':
            echo "Stream ended\n";
            break;
    }
}
```

## Webhooks

The SDK supports webhooks that are called after LangGraph API calls complete:

```php
// Thread-based runs with webhooks
$run = $client->runs()->create($threadId, [
    'assistant_id' => $assistantId,
    'input' => ['message' => 'Process this data'],
    'webhook' => 'https://your-app.com/webhooks/langgraph'
]);

// Stateless runs with webhooks
$client->runs()->createStateless([
    'assistant_id' => $assistantId,
    'input' => ['message' => 'Process this data'],
    'webhook' => 'https://your-app.com/webhooks/langgraph'
]);
```

**Webhook Requirements:**
- Must be a valid URI (up to 65,536 characters)
- Should handle HTTP POST requests from LangGraph Platform
- Called after the API call completes

## Error Handling

The SDK provides specific exception classes for different API errors:

```php
use JasonTame\LangGraphClient\Exceptions\LangGraphException;
use JasonTame\LangGraphClient\Exceptions\NotFoundException;
use JasonTame\LangGraphClient\Exceptions\UnauthorizedException;

try {
    $assistant = $client->assistants()->find('nonexistent-id');
} catch (NotFoundException $e) {
    echo "Assistant not found: " . $e->getMessage();
} catch (UnauthorizedException $e) {
    echo "Authentication failed: " . $e->getMessage();
} catch (LangGraphException $e) {
    echo "API error: " . $e->getMessage();
    
    // Get additional error details
    $response = $e->getResponse();
    $responseData = $e->getResponseData();
    $errorType = $e->getErrorType();
}
```

Available exception classes:
- `LangGraphException` - Base error class
- `BadRequestException` - 400 errors
- `UnauthorizedException` - 401 errors
- `NotFoundException` - 404 errors
- `ConflictException` - 409 errors
- `ValidationException` - 422 errors
- `ServerException` - 500+ errors

## Testing

Run the tests with:

```bash
composer test
```

Run with coverage:

```bash
composer test-coverage
```

## Code Style

Fix code style issues:

```bash
composer format
```

Run static analysis:

```bash
composer analyse
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jason Tame](https://github.com/jasontame)
- Inspired by the [Ruby LangGraph Platform SDK](https://github.com/gysmuller/langgraph-platform)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
