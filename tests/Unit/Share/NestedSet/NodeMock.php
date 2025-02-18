<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\Share\NestedSet;

use Doctrine\Common\Collections\ArrayCollection;
use Shared\Domain\Utils\NestedSet\NodeInterface;
use Shared\Domain\Utils\NestedSet\NodeTrait;
use Shared\Domain\ValueObject\Uuid;

class NodeMock implements NodeInterface
{
    use NodeTrait;

    private int $id = 0;

    private ?int $parentId = null;

    public function __construct()
    {
        $this->id = rand(1, 1000000);
        $this->children = new ArrayCollection();
    }


    public function getId(): int|Uuid
    {
        return $this->id;
    }

    public function getParentId(): null|int|Uuid
    {
        return $this->parentId;
    }

    public function setParentId(int $parentId): void
    {
        $this->parentId = $parentId;
    }

    public function clearChildren(): NodeInterface
    {
        $this->children->clear();
        return $this;
    }

    public function addChild(self|NodeInterface $child): void
    {
        $this->children->add($child);
        /** @var NodeMock $child */
        $child->setParentId($this->id);
    }
}
