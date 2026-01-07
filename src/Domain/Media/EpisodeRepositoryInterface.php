<?php

namespace App\Domain\Media;

interface EpisodeRepositoryInterface
{
    public function findById(int $id): ?Episode;
}
