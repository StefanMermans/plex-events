<?php

namespace App\Domain\Media;

use App\Entity\Release;

interface ReleaseRepositoryInterface
{
    public function findById(int $id): ?Release;
}
