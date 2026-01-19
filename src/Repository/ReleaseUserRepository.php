<?php

namespace App\Repository;

use App\Entity\Release;
use App\Entity\ReleaseUser;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ReleaseUser>
 */
class ReleaseUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReleaseUser::class);
    }

    public function findOrCreate(User $user, Release $release): ReleaseUser
    {
        $existing = $this->findOneBy([
            'user' => $user,
            'seriesRelease' => $release,
        ]);

        if ($existing !== null) {
            return $existing;
        }

        $releaseUser = (new ReleaseUser())
            ->setUser($user)
            ->setSeriesRelease($release);

        $this->getEntityManager()->persist($releaseUser);

        return $releaseUser;
    }
}
