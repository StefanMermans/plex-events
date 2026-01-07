<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Media\EpisodeRepositoryInterface;
use App\Domain\Media\ShowRepositoryInterface;
use App\Dto\PlexEventPayloadDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test', methods: ['POST'])]
    public function index(
        #[MapRequestPayload] PlexEventPayloadDto $dto,
        LoggerInterface                          $logger,
        ShowRepositoryInterface                  $showRepository,
        EpisodeRepositoryInterface               $episodeRepository
    ): Response
    {
        $payload = json_decode($dto->payload, true);
        $event = $payload['event'] ?? 'none';

//        switch ($event) {
//            case 'media.play':
//            case 'media.resume':
//                $this->logWatchEvent($payload, $logger);
//                break;
//            default:
//                break;
//        }
//

        $tvdbLink = array_find($payload['Metadata']['Guid'], static fn(array $value) => str_starts_with($value['id'], 'tvdb://'));
        $tvdbId = substr($tvdbLink['id'], 7);
        $episode = $episodeRepository->findById((int)$tvdbId);
        $show = $showRepository->findById($episode->seriesId);
        $logger->info('Fetched show: ' . $show->title);

        return new Response(null, Response::HTTP_NO_CONTENT);
    }

    protected function logWatchEvent(array $payload, LoggerInterface $logger): void
    {
        $user = $payload['Account']['title'] ?? 'unknown user';
        $mediaTitle = $payload['Metadata']['title'] ?? 'unknown title';
        $logger->info(sprintf('User %s started watching %s', $user, $mediaTitle));
    }
}
