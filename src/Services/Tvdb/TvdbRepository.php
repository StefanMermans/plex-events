<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

class TvdbRepository
{
    public function __construct(
        protected TvdbClient $client,
    )
    {
    }

    protected function getAuthenticatedClient(): TvdbClient
    {
        return $this->client->authenticate();
    }
}
