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

trait HasSoftDeleteTrait
{
    /**
     * @psalm-suppress MissingConstructor
     */
    #[Column(type: 'datetime', name: TableColumn::DELETE_AT, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function getDeletedAtATOM(): ?string
    {
        return $this->deletedAt?->format(DateTimeInterface::ATOM);
    }
}
