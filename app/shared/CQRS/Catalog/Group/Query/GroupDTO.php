<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Group\Query;

use DateTimeImmutable;
use DateTimeInterface;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;

final readonly class GroupDTO
{
    public function __construct(
        public Uuid               $id,
        public Name               $name,
        public Color              $color,
        public ?string            $placeholder = null,
        public ?string            $comment = null,
        public bool               $isSystem = false,
        public bool               $isDefault = false,
        public int                $sort = 0,
        public ?DateTimeImmutable $createdAt = null,
        public ?DateTimeImmutable $updatedAt = null,
        public ?DateTimeImmutable $deletedAt = null,
        public int                $version = 1,
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name->getValue(),
            'color' => $this->color->getValue(),
            'placeholder' => $this->placeholder,
            'comment' => $this->comment,
            'isSystem' => $this->isSystem,
            'isDefault' => $this->isDefault,
            'sort' => $this->sort,
            'createdAt' => $this->createdAt?->format(DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(DateTimeInterface::ATOM),
            'deletedAt' => $this->deletedAt?->format(DateTimeInterface::ATOM),
            'version' => $this->version,
        ];
    }
}
