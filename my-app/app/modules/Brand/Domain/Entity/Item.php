<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Modules\Brand\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior;
use Cycle\ORM\Entity\Behavior\OptimisticLock;
use DateTimeImmutable;
use Modules\Brand\Domain\Repository\ItemRepositoryInterface;
use Modules\Brand\Infrastructure\CycleORM\Table\ItemTable;
use Shared\Domain\ValueObject\Brand\BrandOwner;
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
#[Index(columns: [ItemTable::OWNER])]
#[\Shared\Application\Attribute\Uuid()]
class Item
{
    public const string ROLE = 'brand';

    public const string SKU_PROPERTY_NAME = '{{brand}}';

    public const string F_ID = 'id';
    public const string F_OWNER = 'owner';
    public const string F_NAME = 'name';
    public const string F_COMMENT = 'comment';
    public const string F_IS_SYSTEM = 'isSystem';
    public const string F_AVATAR_ID = 'avatarId';
    public const string F_IS_DEFAULT = 'isDefault';
    public const string F_SKU_PROPERTY = 'skuProperty';
    public const string F_COUNTRY = 'country';
    public const string F_SORT = 'sort';
    public const string F_CREATED_AT = 'createdAt';
    public const string F_UPDATED_AT = 'updatedAt';
    public const string F_DELETED_AT = 'deletedAt';
    public const string F_VERSION = 'version';


    /**
     * @var Uuid
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'uuid', name: ItemTable::ID, primary: true, typecast: 'uuid')]
    protected Uuid $id;
    /**
     * @var BrandOwner
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string', name: ItemTable::OWNER, nullable: false, typecast: BrandOwner::class)]
    protected BrandOwner $owner;

    /**
     * @var Name
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string', name: ItemTable::NAME, nullable: false, default: false, typecast: 'name')]
    protected Name $name;

    /**
     * @var string|null
     */
    #[Column(type: 'string(450)', name: ItemTable::COMMENT, nullable: true)]
    protected ?string $comment = null;

    /**
     * @var bool
     */
    #[Column(type: 'boolean', name: ItemTable::IS_SYSTEM, default: false)]
    protected bool $isSystem = false;

    /**
     * @var string|null
     */
    #[Column(type: 'string', name: ItemTable::SKU_PROPERTY, nullable: true, unique: false)]
    protected ?string $skuProperty = null;

    /**
     * @var string|null
     */
    #[Column(type: 'string', name: ItemTable::COUNTRY, nullable: true, unique: false)]
    protected ?string $country = null;

    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::SORT, default: 0, unsigned: true)]
    protected int $sort = 0;

    /**
     * @var DateTimeImmutable
     */
    #[Column(type: 'datetime', name: ItemTable::CREATED_AT)]
    protected DateTimeImmutable $createdAt;

    /**
     * @var DateTimeImmutable|null
     */
    #[Column(type: 'datetime', name: ItemTable::UPDATED_AT, nullable: true)]
    protected ?DateTimeImmutable $updatedAt = null;

    /**
     * @var DateTimeImmutable|null
     */
    #[Column(type: 'datetime', name: ItemTable::DELETED_AT, nullable: true)]
    protected ?DateTimeImmutable $deletedAt = null;

    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::VERSION, default: 1, unsigned: true)]
    protected int $version = 1;

    /**
     * @var ?string $avatarId
     */
    #[Column(type: 'uuid', name: ItemTable::AVATAR_ID, nullable: true)]
    private ?string $avatarId = null;

    /**
     * @var bool
     */
    #[Column(type: 'boolean', name: ItemTable::IS_DEFAULT, default: false)]
    private bool $isDefault = false;


    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable('now');
        $this->updatedAt = new DateTimeImmutable('now');
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): void
    {
        $this->deletedAt = $deletedAt;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): void
    {
        $this->version = $version;
    }

    public function getSort(): int
    {
        return $this->sort;
    }

    public function setSort(int $sort): void
    {
        $this->sort = $sort;
    }

    public function isNew(): bool
    {
        return $this->updatedAt === null;
    }

    public function markAsDeleted(): self
    {
        $this->deletedAt = new DateTimeImmutable('now');
        return $this;
    }

    public function isDelete(): bool
    {
        return $this->deletedAt === null;
    }

    /**
     * @return BrandOwner
     */
    public function getOwner(): BrandOwner
    {
        return $this->owner;
    }

    /**
     * @param BrandOwner $owner
     */
    public function setOwner(BrandOwner $owner): void
    {
        $this->owner = $owner;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @param Name $name
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param string|null $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return bool
     */
    public function isSystem(): bool
    {
        return $this->isSystem;
    }

    /**
     * @param bool $isSystem
     */
    public function setIsSystem(bool $isSystem): void
    {
        $this->isSystem = $isSystem;
    }

    /**
     * @return string|null
     */
    public function getSkuProperty(): ?string
    {
        return $this->skuProperty;
    }

    /**
     * @param string|null $skuProperty
     */
    public function setSkuProperty(?string $skuProperty): void
    {
        $this->skuProperty = $skuProperty;
    }

    /**
     * @return string|null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }

    /**
     * @param string|null $country
     */
    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    /**
     * @return string|null
     */
    public function getAvatarId(): ?string
    {
        return $this->avatarId;
    }

    /**
     * @param string|null $avatarId
     */
    public function setAvatarId(?string $avatarId): void
    {
        $this->avatarId = $avatarId;
    }

    /**
     * @return bool
     */
    public function isDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     */
    public function setIsDefault(bool $isDefault): void
    {
        $this->isDefault = $isDefault;
    }

    public function __toString(): string
    {
        return $this->name->getValue();
    }
}
