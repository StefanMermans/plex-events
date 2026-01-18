<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use App\Entity\Release;
use App\Domain\Media\ReleaseRepositoryInterface;

class TvdbEpisodeRepository extends TvdbRepository implements ReleaseRepositoryInterface
{

    public function findById(int $id): ?Release
    {
        $data = $this->client->authenticate()->get("/episodes/{$id}")['data'];

        $release = new Release();
        $release->setTvdbId($data['id']);
        $release->setName($data['name']);
        $release->setSeriesId($data['seriesId']);

        return $release;
    }
}
