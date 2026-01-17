<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use App\Domain\Media\Show;
use App\Domain\Media\ShowRepositoryInterface;

class TvdbShowRepository extends TvdbRepository implements ShowRepositoryInterface
{

    public function findById(int $id): ?Show
    {
        $data = $this->client->authenticate()->get("/series/{$id}")['data'];

        return new Show(
            $data['id'],
            $data['name'],
            $this->getEnglishName($data),
        );
    }

    protected function getEnglishName(array $data): null|string
    {
        foreach ($data['aliases'] as $alias) {
            if ($alias['language'] === 'eng') {
                return $alias['name'];
            }
        }

        return null;
    }
}
