<?php

declare(strict_types=1);

namespace App\Domain\Media;

interface ShowRepositoryInterface
{
    public function findById(int $id): ?Show;
}
