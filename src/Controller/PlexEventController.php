<?php

declare(strict_types=1);

namespace App\Controller;

use App\Dto\PlexEventPayloadDto;
use App\Services\Media\MediaEventHandler;
use App\Services\Plex\PlexTranslator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\UriSigner;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

final class PlexEventController extends AbstractController
{
    public function __construct(
        protected UriSigner $uriSigner,
        protected PlexTranslator $plexTranslator,
        protected MediaEventHandler $mediaEventHandler,
        protected LoggerInterface $logger
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

        $plexEventDto = $this->plexTranslator->translate(json_decode($dto->payload, true));
        $this->mediaEventHandler->handle($plexEventDto);

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
}
