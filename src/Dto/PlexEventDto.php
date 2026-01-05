<?php

namespace App\Dto;

class PlexEventDto {
    public function __construct(
        public string $payload,
    ) {}
}
