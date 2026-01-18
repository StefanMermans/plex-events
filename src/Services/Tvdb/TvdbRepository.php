<?php

namespace App\Services\Tvdb;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;

class TvdbRepository
{
    public function __construct(
        protected TvdbClient $client,
        #[Autowire(service: 'cache.tvdb')]
        protected CacheInterface $cache,
    ) {
    }
}
