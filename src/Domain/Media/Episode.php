<?php

namespace App\Domain\Media;

class Episode
{
    public function __construct(
        public int    $id,
        public string $title,
        public ?int   $seriesId,
    )
    {
    }
}
