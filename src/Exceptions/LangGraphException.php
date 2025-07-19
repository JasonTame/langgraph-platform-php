<?php

declare(strict_types=1);

namespace LangGraphPlatform\Exceptions;

use Exception;
use Psr\Http\Message\ResponseInterface;

/**
 * Base exception class for all LangGraph Platform API errors.
 */
class LangGraphException extends Exception
{
    protected ?ResponseInterface $response = null;
    protected array $responseData = [];
    protected ?string $errorType = null;

    public function __construct(
        string $message = '',
        int $code = 0,
        ?Exception $previous = null,
        ?ResponseInterface $response = null,
        array $responseData = []
    ) {
        parent::__construct($message, $code, $previous);

        $this->response = $response;
        $this->responseData = $responseData;
    }

    /**
     * Get the HTTP response that caused this exception.
     */
    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    /**
     * Get the decoded response data.
     */
    public function getResponseData(): array
    {
        return $this->responseData;
    }

    /**
     * Get the error type from the response.
     */
    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    /**
     * Set the error type.
     */
    public function setErrorType(?string $errorType): self
    {
        $this->errorType = $errorType;
        return $this;
    }

    /**
     * Create an exception from an HTTP response.
     */
    public static function fromResponse(ResponseInterface $response, ?Exception $previous = null): self
    {
        $statusCode = $response->getStatusCode();
        $body = $response->getBody()->getContents();
        $responseData = [];

        // Try to decode JSON response
        if ($body) {
            $decoded = json_decode($body, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $responseData = $decoded;
            }
        }

        // Extract error message from response
        $message = $responseData['message'] ?? $responseData['error'] ?? "HTTP {$statusCode} error";
        $errorType = $responseData['type'] ?? null;

        // Create specific exception type based on status code
        $exception = match (true) {
            $statusCode === 400 => new BadRequestException($message, $statusCode, $previous, $response, $responseData),
            $statusCode === 401 => new UnauthorizedException($message, $statusCode, $previous, $response, $responseData),
            $statusCode === 404 => new NotFoundException($message, $statusCode, $previous, $response, $responseData),
            $statusCode === 409 => new ConflictException($message, $statusCode, $previous, $response, $responseData),
            $statusCode === 422 => new ValidationException($message, $statusCode, $previous, $response, $responseData),
            $statusCode >= 500 => new ServerException($message, $statusCode, $previous, $response, $responseData),
            default => new self($message, $statusCode, $previous, $response, $responseData),
        };

        if ($errorType) {
            $exception->setErrorType($errorType);
        }

        return $exception;
    }
}
