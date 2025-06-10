<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use GuzzleHttp\Exception\ServerException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Exception;
use Throwable;

class ServiceException extends Exception
{
    private const DEFAULT_MESSAGE = 'Falha ao processar dados, tente novamente mais tarde.';

    public function __construct(
        protected $message = self::DEFAULT_MESSAGE,
        private Throwable $throwable = new Exception(self::DEFAULT_MESSAGE),
        protected $code = Response::HTTP_INTERNAL_SERVER_ERROR,
        protected string $logChannel = ''
    ) {}

    public function render(): JsonResponse
    {
        $this->message = $this->validateMessage();
        $this->code = $this->validateCode();

        $exceptionResponse = [
            'message' => $this->getMessage(),
            'error' => [
                'code' => $this->code,
                'original_message' => $this->throwable->getMessage(),
                'timestamp' => now()->toISOString(),
                'trace' => $this->throwable->getTrace(),
            ]
        ];

        $this->logException($exceptionResponse);
        return $this->throwException($exceptionResponse);
    }

    private function validateMessage(): string
    {
        return !$this->throwable instanceof ServerException && !$this->throwable instanceof HttpException
            ? $this->throwable->getMessage()
            : $this->message;
    }

    private function validateCode(): int
    {
        return !$this->throwable instanceof ServerException && !$this->throwable instanceof HttpException
            ? $this->throwable->getCode()
            : ($this->code ?: Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    private function throwException(array $exceptionResponse): JsonResponse
    {
        return response()->json($exceptionResponse, $this->code);
    }

    private function logException(array $exceptionResponse): void
    {
        Log::channel($this->logChannel ?: 'service')->error($this->getMessage(), $exceptionResponse);
    }
}
