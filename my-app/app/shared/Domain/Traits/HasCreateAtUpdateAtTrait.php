<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Shared\Domain\Traits;

use Cycle\Annotated\Annotation\Column;
use DateTimeImmutable;
use DateTimeInterface;
use Shared\Infrastructure\Type\TableColumn;


trait HasCreateAtUpdateAtTrait
{

    #[Column(type: 'datetime', name: TableColumn::CREATED_AT)]
    private DateTimeImmutable $createdAt;
    #[Column(type: 'datetime', name: TableColumn::UPDATE_AT, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getCreatedAtATOM(): string
    {
        return $this->createdAt->format(DateTimeInterface::ATOM);
    }

    public function getUpdatedAtATOM(): ?string
    {
        return $this->updatedAt?->format(DateTimeInterface::ATOM);
    }
}
