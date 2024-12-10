<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Showrent\Share\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use DateTimeImmutable;
use DateTimeInterface;
use Shared\Infrastructure\Type\TableColumn;


trait HasHeldTrait
{
    #[Column(type: 'boolean', name: TableColumn::HELD, default: false)]
    private bool $held = false;

    #[Column(type: 'datetime', name: TableColumn::HELD_AT, nullable: true)]
    private ?DateTimeImmutable $heldAt = null;

    #[Column(type: 'datetime', name: TableColumn::DOCUMENT_DATE, nullable: true)]
    private ?DateTimeImmutable $documentDate = null;

    public function isHeld(): bool
    {
        return $this->held;
    }

    public function setHeld(bool $held): self
    {
        $this->held = $held;
        if (!$held) {
            $this->heldAt = null;
        } else {
            $this->heldAt = new DateTimeImmutable('now');
        }
        return $this;
    }

    public function getHeldAt(): ?DateTimeImmutable
    {
        return $this->heldAt;
    }

    public function getHeldAtATOM(): ?string
    {
        return $this->heldAt?->format(DateTimeInterface::ATOM);
    }

    public function getDocumentDate(): ?DateTimeImmutable
    {
        return $this->documentDate;
    }

    public function setDocumentDate(?DateTimeImmutable $documentDate): self
    {
        $this->documentDate = $documentDate;
        return $this;
    }

    public function getDocumentDateATOM(): ?string
    {
        return $this->documentDate?->format(DateTimeInterface::ATOM);
    }
}
