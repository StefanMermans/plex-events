<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Release;

final class MediaEventDto
{
    public function __construct(
        public string $event,
        public Release|null $release = null,
    ) {
    }
}
