<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Shared\CQRS\Catalog\Category\Query;

use DateTimeImmutable;
use DateTimeInterface;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;

final readonly class CategoryDTO
{
    public function __construct(
        public Uuid               $id,
        public Name               $name,
        public ?string            $comment,
        public ?string            $placeholder,
        public bool               $isSystem,
        public ?Uuid              $parentId,
        public int                $sort,
        public bool               $isDefault,
        public int                $left,
        public int                $right,
        public int                $depth,
        public DateTimeImmutable  $createdAt,
        public ?DateTimeImmutable $updatedAt,
        public ?DateTimeImmutable $deletedAt,
        public int                $version
    )
    {
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id->getValue(),
            'name' => $this->name->getValue(),
            'comment' => $this->comment,
            'placeholder' => $this->placeholder,
            'isSystem' => $this->isSystem,
            'parentId' => $this->parentId?->getValue(),
            'sort' => $this->sort,
            'isDefault' => $this->isDefault,
            'left' => $this->left,
            'right' => $this->right,
            'depth' => $this->depth,
            'createdAt' => $this->createdAt->format(DateTimeInterface::ATOM),
            'updatedAt' => $this->updatedAt?->format(DateTimeInterface::ATOM),
            'deletedAt' => $this->deletedAt?->format(DateTimeInterface::ATOM),
            'version' => $this->version,
        ];
    }
}
