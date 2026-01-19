<?php

declare(strict_types=1);

namespace App\Services\Tvdb;

use App\Entity\Release;
use App\Domain\Media\ReleaseRepositoryInterface;
use App\Traits\ParsesTvdbDataToRelease;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReleaseRepository;

class TvdbMovieRepository extends TvdbRepository implements ReleaseRepositoryInterface
{
    use ParsesTvdbDataToRelease;

    public function __construct(
        protected TvdbClient             $client,
        protected ReleaseRepository      $releaseRepository,
        protected EntityManagerInterface $entityManager,
    )
    {
        parent::__construct($client);
    }

    public function findById(int $id): ?Release
    {
        return $this->updateOrCreateReleaseFromTvdbData(
            $this
                ->getAuthenticatedClient()
                ->get("/movies/{$id}/extended")['data']
        );
    }
}
