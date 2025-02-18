<?php

namespace Modules\Group\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior;
use Cycle\ORM\Entity\Behavior\OptimisticLock;
use DateTimeImmutable;
use Modules\Group\Domain\Repository\ItemRepositoryInterface;
use Modules\Group\Infrastructure\CycleORM\Table\ItemTable;
use Shared\Domain\ValueObject\Color;
use Shared\Domain\ValueObject\Group\GroupOwner;
use Shared\Domain\ValueObject\Name;
use Shared\Domain\ValueObject\Uuid;


#[Entity(
    role: Item::ROLE,
    repository: ItemRepositoryInterface::class,
    table: ItemTable::TABLE,
)]
#[Behavior\CreatedAt(
    field: Item::F_CREATED_AT,
)]
#[Behavior\UpdatedAt(
    field: Item::F_UPDATED_AT,
)]
#[Behavior\SoftDelete(
    field: Item::F_DELETED_AT,
)]
#[Behavior\OptimisticLock(
    field: Item::F_VERSION,
    rule: OptimisticLock::RULE_INCREMENT
)]
#[Index(columns: [ItemTable::VERSION, ItemTable::DELETED_AT])]
#[Index(columns: [ItemTable::SORT])]
#[\Shared\Application\Attribute\Uuid()]
/**
 * Class Group Item
 *
 */
class Item
{
    public const string ROLE = 'group';

    public const string DEFAULT_COLOR = '#0080ff';

    public const string F_ID = 'id';
    public const string F_NAME = 'name';
    public const string F_OWNER = 'owner';
    public const string F_COLOR = 'color';
    public const string F_IS_SYSTEM = 'isSystem';
    public const string F_COMMENT = 'comment';
    public const string F_CREATED_AT = 'createdAt';
    public const string F_UPDATED_AT = 'updatedAt';
    public const string F_DELETED_AT = 'deletedAt';
    public const string F_VERSION = 'version';
    public const string F_SORT = 'sort';
    public const string F_PLACEHOLDER = 'placeholder';
    public const string F_IS_DEFAULT = 'isDefault';
    /**
     * @var Uuid $id
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'uuid', name: ItemTable::ID, primary: true, typecast: 'uuid')]
    private Uuid $id;
    /**
     * @var GroupOwner
     */
    #[Column(type: 'string', name: ItemTable::OWNER, nullable: false, default: GroupOwner::DEFAULT->value, typecast: GroupOwner::class)]
    private GroupOwner $owner = GroupOwner::DEFAULT;
    /**
     * @var ?string $placeholder
     */
    #[Column(type: 'string', name: ItemTable::PLACEHOLDER, nullable: true, unique: false)]
    private ?string $placeholder = null;
    /**
     * @var bool $isDefault
     */
    #[Column(type: 'boolean', name: ItemTable::IS_DEFAULT, default: false)]
    private bool $isDefault = false;
    /**
     * @var Name $name
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string', name: ItemTable::NAME, nullable: false, default: false, typecast: 'name')]
    private Name $name;
    /**
     * @var Color $color
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string(7)', name: ItemTable::COLOR, nullable: false, default: self::DEFAULT_COLOR, typecast: 'color')]
    private Color $color;
    /**
     * @var bool $isSystem
     */
    #[Column(type: 'boolean', name: ItemTable::IS_SYSTEM, default: false)]
    private bool $isSystem = false;
    /**
     * @var ?string $comment
     */
    #[Column(type: 'string(450)', name: ItemTable::COMMENT, nullable: true)]
    private ?string $comment = null;
    /**
     * @var int $sort
     */
    #[Column(type: 'integer', name: ItemTable::SORT, default: 0, unsigned: true)]
    private int $sort = 0;
    /**
     * @var DateTimeImmutable $createdAt
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'datetime', name: ItemTable::CREATED_AT)]
    private DateTimeImmutable $createdAt;
    /**
     * @var ?DateTimeImmutable $updatedAt
     */
    #[Column(type: 'datetime', name: ItemTable::UPDATED_AT, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;
    /**
     * @var ?DateTimeImmutable $deletedAt
     */
    #[Column(type: 'datetime', name: ItemTable::DELETED_AT, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;
    /**
     * @var int $version
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'integer', name: ItemTable::VERSION, default: 1, unsigned: true)]
    private int $version;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable('now');
        $this->updatedAt = new DateTimeImmutable('now');
    }

    /**
     * @return GroupOwner
     */
    public function getOwner(): GroupOwner
    {
        return $this->owner;
    }

    /**
     * @param GroupOwner $owner
     */
    public function setOwner(GroupOwner $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * Get the ID
     *
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * Set the ID
     *
     * @param Uuid $id
     */
    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    /**
     * Get the name
     *
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * Set the name
     *
     * @param Name $name
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    /**
     * Get the color
     *
     * @return Color
     */
    public function getColor(): Color
    {
        return $this->color;
    }

    /**
     * Set the color
     *
     * @param Color $color
     */
    public function setColor(Color $color): void
    {
        $this->color = $color;
    }

    /**
     * Get if the group is a system group
     *
     * @return bool
     */
    public function getIsSystem(): bool
    {
        return $this->isSystem;
    }

    /**
     * Set if the group is a system group
     *
     * @param bool $isSystem
     */
    public function setIsSystem(bool $isSystem): void
    {
        $this->isSystem = $isSystem;
    }

    /**
     * Get the comment
     *
     * @return ?string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * Set the comment
     *
     * @param ?string $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * Get the sort order
     *
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * Set the sort order
     *
     * @param int $sort
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * Get the creation date
     *
     * @return DateTimeImmutable
     */
    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * Set the creation date
     *
     * @param DateTimeImmutable $createdAt
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get the update date
     *
     * @return ?DateTimeImmutable
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * Set the update date
     *
     * @param ?DateTimeImmutable $updatedAt
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get the deletion date
     *
     * @return ?DateTimeImmutable
     */
    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * Set the deletion date
     *
     * @param ?DateTimeImmutable $deletedAt
     */
    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * Get the version
     *
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Set the version
     *
     * @param int $version
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * Get the SKU property
     *
     * @return ?string
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    /**
     * Set the SKU property
     *
     * @param ?string $placeholder
     */
    public function setPlaceholder(?string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    /**
     * Get if the group is default
     *
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * Set if the group is default
     *
     * @param bool $isDefault
     */
    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    /**
     * Check if the group is new
     *
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->updatedAt === null;
    }

    /**
     * Mark the group as deleted
     *
     * @return self
     */
    public function markAsDeleted(): self
    {
        $this->deletedAt = new DateTimeImmutable('now');
        return $this;
    }

    /**
     * Check if the group is deleted
     *
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->deletedAt === null;
    }


}
