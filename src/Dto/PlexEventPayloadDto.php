<?php

namespace App\Dto;

class PlexEventPayloadDto {
    public function __construct(
        public string $payload,
    ) {}
}
