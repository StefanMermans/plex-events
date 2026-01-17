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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class PlexEventController extends AbstractController
{
    public function __construct(
        protected ShowRepositoryInterface $showRepository,
        protected EpisodeRepositoryInterface $episodeRepository,
        protected SeriesRepository $seriesRepository,
        protected UriSigner $uriSigner
    ) {
    }

    #[Route('/plex/event', name: 'app_plex_event')]
    public function index(
        Request $request,
        #[MapRequestPayload] PlexEventPayloadDto $dto,
    ): Response {
        if (!$this->uriSigner->check($request->getUri())) {
            throw new AccessDeniedHttpException('Invalid signature');
        }

        $payload = json_decode($dto->payload, true);

        $episode = $this->getEpisode($payload);
        $show = $this->getShow($episode);
        $this->seriesRepository->findOrCreateByTitle($show->originalTitle);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/plex/url', name: 'app_plex_url', methods: ['GET'])]
    public function getUrl(): JsonResponse
    {
        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        $url = $this->generateUrl(
            'app_plex_event',
            ['user' => $user->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $signedUrl = $this->uriSigner->sign($url);

        return new JsonResponse(['url' => $signedUrl]);
    }

    protected function getEpisode(array $payload): Episode
    {
        $tvdbLink = array_find($payload['Metadata']['Guid'], static fn(array $value) => str_starts_with($value['id'], 'tvdb://'));
        $tvdbId = substr($tvdbLink['id'], 7);

        return $this->episodeRepository->findById((int) $tvdbId);
    }

    protected function getShow(Episode $episode): Show
    {
        return $this->showRepository->findById($episode->seriesId);
    }
}
