<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Dto\MediaEventDto;
use Psr\Log\LoggerInterface;

final class MediaEventHandler
{
    public function __construct(
        protected LoggerInterface $logger
    ) {
    }

    public function handle(MediaEventDto $mediaEventDto): void
    {
        $this->logger->info('Media event received', ['event' => $mediaEventDto]);
    }
}