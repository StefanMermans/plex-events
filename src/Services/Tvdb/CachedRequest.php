<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use Symfony\Contracts\Cache\ItemInterface;

trait CachedRequest
{
    const int CACHE_TTL = 86400; // 1 day

    protected function cachedRequest(string $method, string $uri, array $data = [], array $headers = [], bool $useCache = true): array
    {
        $cacheKey = $this->buildRequestCacheKey($method, $uri, $data, $headers);

        if (!$useCache) {
            return $this->request($method, $uri, $data, $headers);
        }

        return $this->cache->get($cacheKey, function (ItemInterface $item) use ($method, $uri, $data, $headers) {
            $item->expiresAfter(self::CACHE_TTL);

            return $this->request($method, $uri, $data, $headers);
        });
    }

    protected function buildRequestCacheKey(string $method, string $uri, array $data = [], array $headers = []): string
    {
        return md5($method . $uri . json_encode($data) . json_encode($headers));
    }

    private function request(string $method, string $uri, array $data, array $headers): array
    {
        $options = [
            'headers' => $this->headers($headers),
        ];

        if ($data) {
            $options['json'] = $data;
        }

        return $this
            ->client
            ->request($method, $this->url($uri), $options)
            ->toArray();
    }

}
