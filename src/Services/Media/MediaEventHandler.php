<?php

declare(strict_types=1);

namespace App\Services\Media;

use App\Dto\MediaEventDto;
use App\Repository\ReleaseUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeImmutable;
use Psr\Log\LoggerInterface;

final class MediaEventHandler
{
    public function __construct(
        protected LoggerInterface $logger,
        protected ReleaseUserRepository $releaseUserRepository,
        protected EntityManagerInterface $entityManager
    ) {
    }

    public function handle(MediaEventDto $mediaEventDto): void
    {
        if ($mediaEventDto->event === 'media.scrobble') {
            $this->markReleaseWatched($mediaEventDto);
        }
    }

    private function markReleaseWatched(MediaEventDto $mediaEventDto): void
    {
        if ($mediaEventDto->release === null) {
            $this->logger->warning('Scrobble event missing release');

            return;
        }

        if ($mediaEventDto->user === null) {
            $this->logger->warning('Scrobble event missing user');

            return;
        }

        $releaseUser = $this->releaseUserRepository->findOrCreate(
            $mediaEventDto->user,
            $mediaEventDto->release
        );

        $releaseUser->setWatchedAt(new DateTimeImmutable());

        $this->entityManager->flush();
    }
}
