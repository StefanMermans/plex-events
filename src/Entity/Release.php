<?php

namespace App\Entity;

use App\Enums\ReleaseType;
use App\Repository\ReleaseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReleaseRepository::class)]
#[ORM\Table(name: '`release`')]
class Release
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'releases')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Series $Series = null;

    #[ORM\Column(enumType: ReleaseType::class)]
    private ?ReleaseType $type = null;

    #[ORM\Column]
    private ?int $number = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $releaseDateTime = null;

    /**
     * @var Collection<int, ReleaseUser>
     */
    #[ORM\OneToMany(targetEntity: ReleaseUser::class, mappedBy: 'seriesRelease', orphanRemoval: true)]
    private Collection $releaseUsers;

    public function __construct()
    {
        $this->releaseUsers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeries(): ?Series
    {
        return $this->Series;
    }

    public function setSeries(?Series $Series): static
    {
        $this->Series = $Series;

        return $this;
    }

    public function getType(): ?ReleaseType
    {
        return $this->type;
    }

    public function setType(ReleaseType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDateTime(): ?\DateTimeImmutable
    {
        return $this->releaseDateTime;
    }

    public function setReleaseDateTime(?\DateTimeImmutable $releaseDateTime): static
    {
        $this->releaseDateTime = $releaseDateTime;

        return $this;
    }

    /**
     * @return Collection<int, ReleaseUser>
     */
    public function getReleaseUsers(): Collection
    {
        return $this->releaseUsers;
    }

    public function addReleaseUser(ReleaseUser $releaseUser): static
    {
        if (!$this->releaseUsers->contains($releaseUser)) {
            $this->releaseUsers->add($releaseUser);
            $releaseUser->setSeriesRelease($this);
        }

        return $this;
    }

    public function removeReleaseUser(ReleaseUser $releaseUser): static
    {
        if ($this->releaseUsers->removeElement($releaseUser)) {
            // set the owning side to null (unless already changed)
            if ($releaseUser->getSeriesRelease() === $this) {
                $releaseUser->setSeriesRelease(null);
            }
        }

        return $this;
    }
}
