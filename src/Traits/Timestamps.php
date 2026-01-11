<?php

declare(strict_types=1);

namespace App\Traits;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait Timestamps
 *
 * Adds createdAt and updatedAt fields to an entity and automatically updates them.
 * When using this trait on an entity, make sure to add the HasLifecycleCallbacks attribute to the entity class
 */
trait Timestamps
{
    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updated_at;

    #[ORM\PrePersist]
    public function setCreatedAtValue(): static
    {
        $this->created_at = new DateTimeImmutable();
        $this->setUpdatedAtValue();

        return $this;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): static
    {
        $this->updated_at = new DateTimeImmutable();

        return $this;
    }
}
