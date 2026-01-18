<?php

declare(strict_types=1);

namespace App\Services\Plex;

use App\Dto\MediaEventDto;
use App\Repository\ReleaseRepository;
use App\Repository\SeriesRepository;
use App\Services\Tvdb\TvdbEpisodeRepository;
use App\Services\Tvdb\TvdbMovieRepository;

class PlexTranslator
{
    public function __construct(
        private SeriesRepository $seriesRepository,
        private ReleaseRepository $releaseRepository,
        private TvdbEpisodeRepository $tvdbEpisodeRepository,
        private TvdbMovieRepository $tvdbMovieRepository,
    ) {
    }

    public function translate(array $data): MediaEventDto
    {
        $event = new MediaEventDto(event: $data['event']);
        $event = $this->addReleaseToMediaEvent($event, $data);

        return $event;
    }

    protected function addReleaseToMediaEvent(MediaEventDto $event, array $data): MediaEventDto
    {
        $release = match ($data['Metadata']['type']) {
            'movie' => $this->tvdbMovieRepository->findById($this->getTvdbId($data)),
            'episode' => $this->tvdbEpisodeRepository->findById($this->getTvdbId($data)),
            default => throw new \Exception("Unknown media type: {$data['Metadata']['type']}"),
        };

        $event->release = $release;

        return $event;
    }

    protected function getTvdbId(array $data): int
    {
        $ids = $data['Metadata']['Guid'];

        foreach ($ids as $id) {
            if (str_starts_with($id['id'], 'tvdb://')) {
                return (int) str_replace('tvdb://', '', $id['id']);
            }
        }

        throw new \Exception("No tvdb id found");
    }
}