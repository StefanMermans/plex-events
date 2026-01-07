<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use App\Domain\Media\Episode;
use App\Domain\Media\EpisodeRepositoryInterface;

class TvdbEpisodeRepository extends TvdbRepository implements EpisodeRepositoryInterface {

    public function findById(int $id): ?Episode {
        $data = $this->client->authenticate()->get("/episodes/{$id}")['data'];

        return new Episode(
            $data['id'],
            $data['name'],
            $data['seriesId'],
        );
    }
}
