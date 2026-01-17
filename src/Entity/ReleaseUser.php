<?php

namespace App\Entity;

use App\Repository\ReleaseUserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleaseUserRepository::class)]
class ReleaseUser
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'releaseUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'releaseUsers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Release $seriesRelease = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $watchedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getSeriesRelease(): ?Release
    {
        return $this->seriesRelease;
    }

    public function setSeriesRelease(?Release $seriesRelease): static
    {
        $this->seriesRelease = $seriesRelease;

        return $this;
    }

    public function getWatchedAt(): ?\DateTimeImmutable
    {
        return $this->watchedAt;
    }

    public function setWatchedAt(?\DateTimeImmutable $watchedAt): static
    {
        $this->watchedAt = $watchedAt;

        return $this;
    }
}
