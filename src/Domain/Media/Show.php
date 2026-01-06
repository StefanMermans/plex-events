<?php

declare(strict_types=1);

namespace App\Domain\Media;

class Show {
    public function __construct(
        public int $id,
        public string $title,
    ) {}
}
