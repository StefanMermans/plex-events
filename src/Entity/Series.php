<?php

namespace App\Entity;

use App\Enums\SeriesStatus;
use App\Enums\SeriesType;
use App\Repository\SeriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeriesRepository::class)]
class Series
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(enumType: SeriesStatus::class)]
    private ?SeriesStatus $status = SeriesStatus::Unknown;

    /**
     * @var Collection<int, Release>
     */
    #[ORM\OneToMany(targetEntity: Release::class, mappedBy: 'series')]
    private Collection $releases;

    public function __construct()
    {
        $this->releases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?SeriesType
    {
        return $this->type;
    }

    public function setType(SeriesType $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStatus(): ?SeriesStatus
    {
        return $this->status;
    }

    public function setStatus(SeriesStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Release>
     */
    public function getReleases(): Collection
    {
        return $this->releases;
    }

    public function addRelease(Release $release): static
    {
        if (!$this->releases->contains($release)) {
            $this->releases->add($release);
            $release->setSeriesId($this);
        }

        return $this;
    }

    public function removeRelease(Release $release): static
    {
        if ($this->releases->removeElement($release)) {
            // set the owning side to null (unless already changed)
            if ($release->getSeriesId() === $this) {
                $release->setSeriesId(null);
            }
        }

        return $this;
    }
}
