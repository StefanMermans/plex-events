<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use Symfony\Contracts\Cache\ItemInterface;

trait CachedMedia {
    const CACHE_TTL = 86400; // 1 day

    protected function cache(string $key, callable $callback) {
        return $this->cache->get($key, static function(ItemInterface $item) use ($callback) {
            $item->expiresAfter(self::CACHE_TTL);
            return $callback($item);
        });
    }
}
