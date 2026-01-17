<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Media\Episode;
use App\Domain\Media\EpisodeRepositoryInterface;
use App\Domain\Media\Show;
use App\Domain\Media\ShowRepositoryInterface;
use App\Dto\PlexEventPayloadDto;
use App\Repository\SeriesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class PlexEventController extends AbstractController
{
    public function __construct(
        protected ShowRepositoryInterface    $showRepository,
        protected EpisodeRepositoryInterface $episodeRepository,
        protected SeriesRepository $seriesRepository
    )
    {
    }

    #[Route('/plex/event', name: 'app_plex_event')]
    public function index(
        #[MapRequestPayload] PlexEventPayloadDto $dto,
    ): Response
    {
        $payload = json_decode($dto->payload, true);

        $episode = $this->getEpisode($payload);
        $show = $this->getShow($episode);
        $this->seriesRepository->findOrCreateByTitle($show->originalTitle);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    protected function getEpisode(array $payload): Episode
    {
        $tvdbLink = array_find($payload['Metadata']['Guid'], static fn(array $value) => str_starts_with($value['id'], 'tvdb://'));
        $tvdbId = substr($tvdbLink['id'], 7);

        return $this->episodeRepository->findById((int)$tvdbId);
    }

    protected function getShow(Episode $episode): Show
    {
        return $this->showRepository->findById($episode->seriesId);
    }
}
