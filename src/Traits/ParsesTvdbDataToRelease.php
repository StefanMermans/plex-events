<?php

declare(strict_types=1);

namespace App\Traits;

use App\Entity\Release;
use App\Enums\ReleaseType;
use DateTimeImmutable;

trait ParsesTvdbDataToRelease
{
    protected function fillReleaseData(Release $release, array $data): Release
    {
        $release->setTitle($data['name']);
        $release->setTitleEn($this->getEnglishTitle($data));
        $release->setTvdbId((string)$data['id']);
        $release->setType($this->getType($data));
        $release->setReleaseDateTime($this->getReleaseDate($data));

        return $release;
    }

    protected function persistRelease(Release $release): void
    {
        $this->entityManager->persist($release);
        $this->entityManager->flush();
    }

    protected function getType(array $data): ReleaseType
    {
        if ($data['isMovie']) {
            return ReleaseType::Movie;
        }

        return ReleaseType::Episode;
    }

    protected function updateOrCreateReleaseFromTvdbData(array $data): Release
    {
        $release = $this->releaseRepository->findByTvdbId((string)$data['id']);

        if (!$release) {
            $release = new Release();
        }

        $this->fillReleaseData($release, $data);
        $this->persistRelease($release);

        return $release;
    }

    protected function getEnglishTitle(array $data): ?string
    {
        if (!isset($data['aliases']) || $data['aliases'] === []) {
            return null;
        }

        foreach ($data['aliases'] as $alias) {
            if ($alias['language'] === 'eng') {
                return $alias['name'];
            }
        }

        return null;
    }

    protected function getReleaseDate(array $data): DateTimeImmutable
    {
        $releaseDate = new DateTimeImmutable();
        $releaseDate->setDate(year: (int)$data['year'], month: 1, day: 1);

        return $releaseDate;
    }
}
