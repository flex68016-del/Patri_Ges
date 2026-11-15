<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ApiClient
{
    public function get(string $service, string $path, array $query = [])
    {
        return $this->request('get', $service, $path, ['query' => $query]);
    }

    public function post(string $service, string $path, array $data = [])
    {
        return $this->request('post', $service, $path, ['json' => $data]);
    }

    private function request(string $method, string $service, string $path, array $options = [])
    {
        $baseUrl = match ($service) {
            'auth' => env('AUTH_SERVICE_URL', 'http://localhost:8001'),
            'patrimoine' => env('PATRIMOINE_SERVICE_URL', 'http://localhost:8002'),
            'operations' => env('OPERATIONS_SERVICE_URL', 'http://localhost:8003'),
            default => throw new \InvalidArgumentException("Service inconnu: {$service}"),
        };

        $pending = Http::acceptJson()->timeout(10);

        if ($token = session('token')) {
            $pending = $pending->withToken($token);
        }

        return $pending->{$method}($baseUrl.$path, $options['json'] ?? $options['query'] ?? []);
    }
}
