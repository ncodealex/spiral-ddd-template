<?php

namespace Modules\UnitsOfMeasurement\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior;
use Cycle\ORM\Entity\Behavior\OptimisticLock;
use DateTimeImmutable;
use Modules\UnitsOfMeasurement\Domain\Repository\ItemRepositoryInterface;
use Modules\UnitsOfMeasurement\Infrastructure\CycleORM\Table\ItemTable;
use Shared\Domain\ValueObject\PackagingType;
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
class Item
{
    public const string ROLE = 'unit_of_measurement';

    public const string F_ID = 'id';
    public const string F_NAME = 'name';
    public const string F_PRECISION = 'precision';
    public const string F_FOR_PACKAGING = 'forPackaging';
    public const string F_IS_SYSTEM = 'isSystem';
    public const string F_IS_DEFAULT = 'isDefault';
    public const string F_CREATED_AT = 'createdAt';
    public const string F_UPDATED_AT = 'updatedAt';
    public const string F_DELETED_AT = 'deletedAt';
    public const string F_VERSION = 'version';
    public const string F_SORT = 'sort';

    /**
     * @var Uuid
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'uuid', name: ItemTable::ID, primary: true, typecast: 'uuid')]
    protected Uuid $id;

    /**
     * @var string
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string', name: ItemTable::NAME, nullable: false)]
    protected string $name;

    /**
     * @var PackagingType
     */
    #[Column(type: 'string', name: ItemTable::FOR_PACKAGING, nullable: false, default: PackagingType::NOT_USED->value, typecast: PackagingType::class)]
    protected PackagingType $forPackaging = PackagingType::NOT_USED;

    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::PRECISION, default: 0)]
    protected int $precision = 0;

    /**
     * @var bool
     */
    #[Column(type: 'boolean', name: ItemTable::IS_SYSTEM, default: false)]
    protected bool $isSystem = false;

    /**
     * @var bool
     */
    #[Column(type: 'boolean', name: ItemTable::IS_DEFAULT, default: false)]
    protected bool $isDefault = false;

    /**
     * @var DateTimeImmutable
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'datetime', name: ItemTable::CREATED_AT)]
    private DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    #[Column(type: 'datetime', name: ItemTable::UPDATED_AT, nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    /**
     * @var DateTimeImmutable|null
     */
    #[Column(type: 'datetime', name: ItemTable::DELETED_AT, nullable: true)]
    private ?DateTimeImmutable $deletedAt = null;

    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::VERSION, default: 1, unsigned: true)]
    private int $version = 1;

    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::SORT, default: 0, unsigned: true)]
    private int $sort = 0;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable('now');
        $this->updatedAt = new DateTimeImmutable('now');
    }

    /**
     * @return Uuid
     */
    public function getId(): Uuid
    {
        return $this->id;
    }

    /**
     * @param Uuid $id
     *
     * @return void
     */
    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    /******
     *
     *   Entity fields
     *
     *****/

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @param DateTimeImmutable $createdAt
     *
     * @return void
     */
    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    /**
     * @param DateTimeImmutable|null $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    /**
     * @param DateTimeImmutable|null $deletedAt
     *
     * @return void
     */
    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param int $version
     *
     * @return void
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return void
     */
    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->updatedAt === null;
    }

    /**
     * @return $this
     */
    public function markAsDeleted(): self
    {
        $this->deletedAt = new DateTimeImmutable('now');
        return $this;
    }

    /**
     * @return bool
     */
    public function isDelete(): bool
    {
        return $this->deletedAt === null;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getPrecision(): int
    {
        return $this->precision;
    }

    public function setPrecision(int $precision): self
    {
        $this->precision = $precision;
        return $this;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->isSystem;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    public function setIsDefault(bool $isDefault): self
    {
        $this->isDefault = $isDefault;
        return $this;
    }

    /**
     * @return PackagingType
     */
    public function getForPackaging(): PackagingType
    {
        return $this->forPackaging;
    }

    /**
     * @param PackagingType $forPackaging
     *
     * @return Item
     */
    public function setForPackaging(PackagingType $forPackaging): self
    {
        $this->forPackaging = $forPackaging;
        return $this;
    }
}
