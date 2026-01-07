<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;


class TvdbClient
{
    use CachedRequest;

    public function __construct(
        #[Autowire(service: 'cache.tvdb')]
        protected readonly CacheInterface $cache,
        protected readonly HttpClientInterface $client,
        protected readonly string $baseUrl,
        protected readonly string $apiKey,
        protected null|string $token = null,
    ) {}

    public function get(string $uri): array
    {
        return $this->cachedRequest('GET', $uri);
    }

    public function post(string $uri, array $data = [], array $headers = []): array
    {
        return $this->cachedRequest('POST', $uri, $data, $headers);
    }

    public function authenticate(): self {
        $token = $this->cache->get('auth_token', function(ItemInterface $item) {
            $response = $this->post('/login', [
                'apikey' => $this->apiKey,
            ]);

            if($response['status'] !== 'success') {
                throw new \RuntimeException('Failed to authenticate with TVDB API');
            }

            if (!isset($response['data']['token'])) {
                throw new \RuntimeException('TVDB API response missing auth token');
            }

            $tokenExp = $this->getExpFromToken($response['data']['token']);
            $item->expiresAt($tokenExp);

            return $response['data']['token'];
        });

        $this->token = $token;

        return $this;
    }

    protected function headers(array $override = []): array {
        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ];

        if ($this->token) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }

        return array_merge($headers, $override);
    }

    protected function getExpFromToken(string $token): \DateTimeImmutable {
        $parts = explode('.', $token);
        if (count($parts) !== 3) {
            throw new \InvalidArgumentException('Invalid JWT token format');
        }

        $payload = json_decode(base64_decode($parts[1]), true);
        if (!isset($payload['exp'])) {
            throw new \InvalidArgumentException('JWT token does not contain exp claim');
        }

        return (new \DateTimeImmutable())->setTimestamp($payload['exp']);
    }

    protected function url(string $uri): string {
        return $this->baseUrl . $uri;
    }
}
