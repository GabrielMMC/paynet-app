<?php

namespace App\Apis;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use RuntimeException;
use Throwable;

abstract class BaseApi
{
    private const MAX_ATTEMPTS = 3;

    private int $expectatedHttpStatus;
    private string $method;
    private array $params;

    protected string $baseUrl;
    protected string $logChannel;

    protected function get(array $params = []): self
    {
        $this->expectatedHttpStatus = Response::HTTP_OK;
        $this->params = $params;
        $this->method = 'get';

        return $this;
    }

    protected function post(array $params): self
    {
        $this->expectatedHttpStatus = Response::HTTP_CREATED;
        $this->params = $params;
        $this->method = 'post';

        return $this;
    }

    protected function expectedResponse(int $expectatedHttpStatus): self
    {
        $this->expectatedHttpStatus = $expectatedHttpStatus;
        return $this;
    }

    public function fetch(string $url = ''): ClientResponse
    {
        $this->validateBuilderMethods();

        $attempts = 0;
        $exception = null;

        while ($attempts < self::MAX_ATTEMPTS) {
            try {
                $response = Http::{$this->method}($this->baseUrl . $url, $this->params);
                $response->throwUnlessStatus($this->expectatedHttpStatus);

                return $response;
            } catch (Throwable $th) {
                Log::channel($this->logChannel)
                    ->error("Falha ao consumir API", ['error' => $th->getMessage()]);

                $exception = $th;
                $attempts++;
            }
        }

        throw new HttpException(
            $exception->getCode() ?: Response::HTTP_SERVICE_UNAVAILABLE,
            'external_api_error'
        );
    }

    private function validateBuilderMethods(): void
    {
        if (!$this->params && $this->method !== 'get') {
            throw new RuntimeException("Set a valid params");
        }
        if (!$this->expectatedHttpStatus) {
            throw new RuntimeException("Set a valid status");
        }
        if (!$this->method) {
            throw new RuntimeException("Set a valid method");
        }
    }
}
