<?php

declare(strict_types=1);

namespace App\Controller;

use App\Domain\Media\ShowRepositoryInterface;
use App\Dto\PlexEventDto;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class TestController extends AbstractController
{
    #[Route('/test', name: 'app_test')]
    public function index(
        #[MapRequestPayload] PlexEventDto $dto,
        LoggerInterface $logger,
        ShowRepositoryInterface $showRepository,
    ): JsonResponse
    {
        $payload = json_decode($dto->payload, true);
        $event = $payload['event'] ?? 'none';

        switch ($event) {
            case 'media.play':
            case 'media.resume':
                $this->logWatchEvent($payload, $logger);
                break;
            default:
                break;
        }

        $show = $showRepository->findById(416744);
        $logger->info('Fetched show: ' . $show->title);

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/TestController.php',
        ]);
    }

    protected function logWatchEvent(array $payload, LoggerInterface $logger): void
    {
        $user = $payload['Account']['title'] ?? 'unknown user';
        $mediaTitle = $payload['Metadata']['title'] ?? 'unknown title';
        $logger->info(sprintf('User %s started watching %s', $user, $mediaTitle));
    }
}
