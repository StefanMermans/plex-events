<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use App\Entity\Release;
use App\Domain\Media\ReleaseRepositoryInterface;
use App\Enums\ReleaseType;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\Cache\CacheInterface;
use App\Repository\ReleaseRepository;

class TvdbMovieRepository extends TvdbRepository implements ReleaseRepositoryInterface
{
    public function __construct(
        protected TvdbClient $client,
        #[Autowire(service: 'cache.tvdb')]
        protected CacheInterface $cache,
        protected ReleaseRepository $releaseRepository,
        protected EntityManagerInterface $entityManager,
        protected LoggerInterface $logger,
    ) {
        parent::__construct($client, $cache);
    }

    public function findById(int $id): ?Release
    {
        $data = $this->client->authenticate()->get("/movies/{$id}")['data'];
        $release = $this->releaseRepository->findByTvdbId((string) $id);

        if (!$release) {
            $release = new Release();
        }

        $this->logger->info('Found movie', ['data' => $data]);

        $release->setTitle($data['name']);
        $release->setTvdbId((string) $data['id']);
        $release->setType(ReleaseType::Movie);

        $releaseDate = new DateTimeImmutable();
        $releaseDate->setDate(year: (int) $data['year'], month: 1, day: 1);
        $release->setReleaseDateTime($releaseDate);

        $this->entityManager->persist($release);
        $this->entityManager->flush();

        return $release;
    }
}
