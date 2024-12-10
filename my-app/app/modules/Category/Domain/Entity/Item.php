<?php

namespace Modules\Category\Domain\Entity;

use Cycle\Annotated\Annotation\Column;
use Cycle\Annotated\Annotation\Entity;
use Cycle\Annotated\Annotation\Relation\HasMany;
use Cycle\Annotated\Annotation\Relation\RefersTo;
use Cycle\Annotated\Annotation\Table\Index;
use Cycle\ORM\Entity\Behavior;
use Cycle\ORM\Entity\Behavior\OptimisticLock;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Modules\Category\Infrastructure\CycleORM\Table\ItemTable;
use Shared\Domain\Utils\NestedSet\NodeInterface;
use Shared\Domain\ValueObject\Category\CategoryOwner;
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
    column: ItemTable::DELETED_AT
)]
#[Behavior\OptimisticLock(
    field: Item::F_VERSION,
    rule: OptimisticLock::RULE_INCREMENT
)]
#[Index(columns: [ItemTable::VERSION, ItemTable::DELETED_AT])]
#[Index(columns: [ItemTable::LEFT, ItemTable::DEPTH, ItemTable::SORT])]
#[Index(columns: [ItemTable::SORT])]
#[\Shared\Application\Attribute\Uuid()]
/**
 * Class Category Item
 *
 * @implements NodeInterface
 * @psalm-suppress PropertyNotSetInConstructor
 */
class Item implements NodeInterface
{
    public const string ROLE = 'category';
    public const string F_ID = 'id';
    public const string F_OWNER = 'owner';
    public const string F_PLACEHOLDER = 'placeholder';

    public const string F_NAME = 'name';
    public const string F_COMMENT = 'comment';
    public const string F_IS_SYSTEM = 'isSystem';
    public const string F_PARENT_ID = 'parentId';
    public const string F_SORT = 'sort';
    public const string F_IS_DEFAULT = 'isDefault';
    public const string F_LEFT = 'left';
    public const string F_RIGHT = 'right';
    public const string F_DEPTH = 'depth';
    public const string F_CREATED_AT = 'createdAt';
    public const string F_UPDATED_AT = 'updatedAt';
    public const string F_DELETED_AT = 'deletedAt';
    public const string F_VERSION = 'version';
    /**
     * @var Uuid
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'uuid', name: ItemTable::ID, primary: true, typecast: 'uuid')]
    private Uuid $id;
    #[Column(type: 'string', nullable: false, default: CategoryOwner::DEFAULT->value, typecast: CategoryOwner::class)]
    private CategoryOwner $owner = CategoryOwner::DEFAULT;
    /**
     * @var string|null
     */
    #[Column(type: 'string', name: ItemTable::PLACEHOLDER, nullable: true, unique: false)]
    private ?string $placeholder = null;
    /**
     * @var Name
     * @psalm-suppress PropertyNotSetInConstructor
     */
    #[Column(type: 'string', name: ItemTable::NAME, nullable: false, default: false, typecast: 'name')]
    private Name $name;
    /**
     * @var string|null
     */
    #[Column(type: 'string', name: ItemTable::COMMENT, nullable: true, size: 455)]
    private ?string $comment = null;
    /**
     * @var bool
     *
     */
    #[Column(type: 'boolean', name: ItemTable::IS_SYSTEM, default: false)]
    private bool $isSystem = false;

    /**
     * @var Item|null
     */
    #[RefersTo(
        target: Item::class,
        nullable: true,
        innerKey: self::F_PARENT_ID,
        outerKey: self::F_ID,
        fkOnDelete: 'SET NULL'
    )]
    private ?Item $parent = null;

    /**
     * @var ArrayCollection<array-key,Item|NodeInterface> $children
     */
    #[HasMany(
        target: self::class,
        innerKey: self::F_ID,
        outerKey: self::F_PARENT_ID,
        nullable: true,
        orderBy: [self::F_LEFT => 'ASC', self::F_DEPTH => 'ASC', self::F_SORT => 'ASC'],
        collection: 'doctrine'
    )]
    private ArrayCollection $children;

    /**
     * @var Uuid|null
     */
    #[Column(type: 'uuid', name: ItemTable::PARENT_ID, nullable: true, typecast: 'uuid')]
    private ?Uuid $parentId = null;
    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::SORT, default: 0, unsigned: true)]
    private int $sort = 0;
    /**
     * @var bool
     */
    #[Column(type: 'boolean', name: ItemTable::IS_DEFAULT, default: false)]
    private bool $isDefault = false;
    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::LEFT, unsigned: true)]
    private int $left = 0;
    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::RIGHT, unsigned: true)]
    private int $right = 0;
    /**
     * @var int
     */
    #[Column(type: 'integer', name: ItemTable::DEPTH, unsigned: true)]
    private int $depth = 0;
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

    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable('now');
        $this->updatedAt = new DateTimeImmutable('now');
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
     * @return Item|null
     */
    public function getParent(): ?Item
    {
        return $this->parent;
    }

    /**
     * @param Item|null $parent
     */
    public function setParent(?Item $parent): void
    {
        $this->parent = $parent;
        $this->parentId = $parent?->getId();
        if ($parent === null) {
            $this->depth = 0;
        }
    }

    /**
     * @return Uuid|null
     */
    public function getParentId(): ?Uuid
    {
        return $this->parentId;
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

    /**
     * @return int
     */
    public function getRight(): int
    {
        return $this->right;
    }

    /**
     * @param int $right
     */
    public function setRight(int $right): void
    {
        $this->right = $right;
    }

    /**
     * @return int
     */
    public function getDepth(): int
    {
        return $this->depth;
    }

    /**
     * @param int $depth
     */
    public function setDepth(int $depth): void
    {
        $this->depth = $depth;
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
     * @return Item
     */
    public function setId(Uuid $id): self
    {
        $this->id = $id;
        return $this;
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
     * @return DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
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
     */
    public function setVersion(int $version): void
    {
        $this->version = $version;
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
     * @return CategoryOwner
     */
    public function getOwner(): CategoryOwner
    {
        return $this->owner;
    }

    /**
     * @param CategoryOwner $owner
     *
     * @return Item
     */
    public function setOwner(CategoryOwner $owner): self
    {
        $this->owner = $owner;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlaceholder(): ?string
    {
        return $this->placeholder;
    }

    /**
     * @param string|null $placeholder
     */
    public function setPlaceholder(?string $placeholder): void
    {
        $this->placeholder = $placeholder;
    }

    /**
     * @param NodeInterface|Item $child
     *
     * @return void
     */
    public function addChild(NodeInterface|Item $child): void
    {
        /** @psalm-suppress InvalidArgument */
        if (!$this->children->contains($child)) {
            $this->children->add($child);
            /** @var Item $child */
            $child->setParent($this);
            $child->setDepth($this->depth + 1);
            $this->updateLeftRight();
            $this->updateDepthRecursive();
        }
    }

    private function updateLeftRight(): void
    {
        $left = $this->left;
        $this->setLeftRight($this, $left);
    }

    private function setLeftRight(Item $item, int &$left): void
    {
        $item->setLeft($left ++);
        $item->setSort($item->getLeft());
        /** @var Item $child */
        foreach ($item->getChildren() as $child) {
            $this->setLeftRight($child, $left);
        }
        $item->setRight($left ++);
    }

    /**
     * @return int
     */
    public function getLeft(): int
    {
        return $this->left;
    }

    /**
     * @param int $left
     */
    public function setLeft(int $left): void
    {
        $this->left = $left;
    }

    public function getChildren(): ArrayCollection
    {
        return $this->children;
    }

    public function updateDepthRecursive(): void
    {
        $this->updateDepthForNode($this, $this->depth);
    }

    protected function updateDepthForNode(self $node, int $depth): void
    {
        if (!$node->getChildren()->isEmpty()) {
            foreach ($this->children as $child) {
                $child->setDepth($this->depth + 1);
                /** @var Item $child */
                $child->updateDepthForNode($child, $depth);
            }
        }
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

    public function clearChildren(): self
    {
        $this->children->clear();
        return $this;
    }

    public function removeChild(Item|NodeInterface $child): void
    {
        /** @psalm-suppress InvalidArgument */
        if ($this->children->contains($child)) {
            $this->children->removeElement($child);
            /** @var Item $child */
            $child->setParent(null);
            $this->updateLeftRight();
            $this->updateDepthRecursive();
        }
    }


}
