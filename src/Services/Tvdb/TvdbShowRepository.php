<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use App\Domain\Media\Show;
use App\Domain\Media\ShowRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;

class TvdbShowRepository implements ShowRepositoryInterface {
    use CachedMedia;

    public function __construct(
        private TvdbClient $client,
        #[Autowire(service: 'cache.tvdb')]
        private CacheInterface $cache,
    )
    {
    }

    public function findById(int $id): ?Show {
        return $this->cache("show_{$id}", function() use ($id) {
            $data = $this->client->authenticate()->get("/series/{$id}");

            return new Show($data['data']['id'], $data['data']['name']);
        });
    }
}
