<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

declare(strict_types=1);

namespace Tests\Unit\Category\Item;

use Cycle\Database\Driver\SQLite\Query\SQLiteSelectQuery;
use Cycle\ORM\Transaction\UnitOfWork;
use Database\Factory\Category\DBItemFactory;
use Doctrine\Common\Collections\ArrayCollection;
use Modules\Category\Domain\Repository\ItemRepositoryInterface;
use Shared\Domain\Utils\NestedSet\NestedSetService;
use Shared\Domain\ValueObject\Category\CategoryOwner;
use Tests\BaseTestCase;
use Throwable;

/**
 * Class CategoryRepositoryTest
 *
 * @psalm-suppress PossiblyInvalidMethodCall
 * @psalm-suppress PossiblyNullReference
 * @psalm-suppress PossiblyFalseReference
 */
class CategoryRepositoryTest extends BaseTestCase
{

    /**
     * @var ItemRepositoryInterface
     * @psalm-suppress PropertyNotSetInConstructor
     */
    protected ItemRepositoryInterface $repository;

    public function test_persist_roots(): void
    {
        $result = $this->persistRoots();
        $this->assertTrue($result, 'Persist roots failed');
    }

    protected function persistRoots(): bool
    {
        $roots = $this->getRoots();
        NestedSetService::calculateNodes($roots);
        $UoW = new UnitOfWork($this->getOrm());
        foreach ($roots as $root) {
            $UoW->persistDeferred($root);
        }
        return $UoW->run()->isSuccess();
    }

    protected function getRoots(): ArrayCollection
    {

        $factory = DBItemFactory::new();
        // Root 1

        $root_1 = $factory->createOne();

        $root_1_child_1_1 = $factory->createOne();

        $root_1_child_1_2 = $factory->createOne();

        $root_1_child_1_3 = $factory->createOne();

        $root_1_child_1_2->addChild($root_1_child_1_3);
        $root_1_child_1_1->addChild($root_1_child_1_2);
        /// NODE 1
        $root_1->addChild($root_1_child_1_1);

        //// NODE 2
        $root_1_child_2_1 = $factory->createOne();
        ///  NODE 2 -> Child 1
        $root_1_child_2_2 = $factory->createOne();
        $root_1_child_2_3 = $factory->createOne();
        ///  NODE 2 -> Child 2
        $root_1_child_2_2_1 = $factory->createOne();
        $root_1_child_2_2_2 = $factory->createOne();

        $root_1_child_2_2->addChild($root_1_child_2_3);
        $root_1_child_2_2_1->addChild($root_1_child_2_2_2);
        $root_1_child_2_1->addChild($root_1_child_2_2);
        $root_1_child_2_1->addChild($root_1_child_2_2_1);

        $root_1->addChild($root_1_child_2_1);


        //// NODE 3
        $root_1_child_3_1 = $factory->createOne();

        $root_1_child_3_2 = $factory->createOne();

        $root_1_child_3_3 = $factory->createOne();

        $root_1_child_3_2->addChild($root_1_child_3_3);

        $root_1_child_3_1->addChild($root_1_child_3_2);

        $root_1->addChild($root_1_child_3_1);
        /*
         *   Root 1 Schema
         *   (1, 24, 0).      (2, 7, 1)         (3, 6, 2).      (4, 5, 3)
         *   root     |------> child 1.1 ------> child 1.2 ----> child 1.3
         *                    (8, 17, 1).        (9, 16, 2).      (10, 11, 3)
         *            |------> child 2.1 -----> child 2.2 ----> child 2.3
         *                                          | (12, 15, 2).   (13, 14, 3)
         *                                      child 2.2.1 ----> child 2.2.2
         *                    (18, 23, 1).        (19, 22, 2).      (20, 21, 3)
         *            |------> child 3.1 -----> child 3.2 ----> child 3.3
         *
         * */

        $root_2 = $factory->createOne();

        /*
         *   Root 2 Schema
         *   (25, 38, 0).     (26, 27, 1)
         *   root     |------> child 1.1
         *                    (28, 29, 1)
         *            |------> child 2.1
         *                    (30, 37, 1)
         *            |------> child 3.1
         *                                      (31, 36, 2)
         *                              |------> child 3.1.1
         *                                                        (32, 35, 3)
         *                                                |------> child 3.1.1.1
         *                                                                                (33, 34, 4)
         *                                                                      |------> child 3.1.1.1.1
         *
         * */

        $root_2_child_1_1 = $factory->createOne();

        $root_2_child_2_1 = $factory->createOne();

        $root_2_child_3_1 = $factory->createOne();

        $root_2_child_3_1_1 = $factory->createOne();

        $root_2_child_3_1_1_1 = $factory->createOne();

        $root_2_child_3_1_1_1_1 = $factory->createOne();

        $root_2_child_3_1_1_1->addChild($root_2_child_3_1_1_1_1);

        $root_2_child_3_1_1->addChild($root_2_child_3_1_1_1);

        $root_2_child_3_1->addChild($root_2_child_3_1_1);
        $root_2->addChild($root_2_child_1_1);
        $root_2->addChild($root_2_child_2_1);
        $root_2->addChild($root_2_child_3_1);


        return new ArrayCollection([$root_1, $root_2]);
    }

    public function test_get_tree_from_db(): void
    {
        $this->showDatabaseQueries();
        $result = $this->persistRoots();
        $this->assertTrue($result, 'Persist roots failed');

        /** @var SQLiteSelectQuery $select */
        $tree = $this->repository->tree(CategoryOwner::DEFAULT);
        $this->assertInstanceOf(ArrayCollection::class, $tree);
        $this->assertNotEmpty($tree, 'Tree is empty');
        $this->assertCount(2, $tree, 'Roots count is not correct');
        $this->assertEquals(1, $tree->first()?->getLeft(), 'Root 1 left is not correct');
        $this->assertEquals(38, $tree->last()?->getRight(), 'Root 2 right is not correct');
    }

    public function test_get_flat_from_db(): void
    {
        $this->showDatabaseQueries();
        $result = $this->persistRoots();
        $this->assertTrue($result, 'Persist roots failed');

        /** @var SQLiteSelectQuery $select */
        $flat = $this->repository->flat(CategoryOwner::DEFAULT);
        $this->assertInstanceOf(ArrayCollection::class, $flat);
        $this->assertNotEmpty($flat, 'Flat is empty');
        $this->assertCount(19, $flat, 'Flat count is not correct');
    }

    /**
     * @throws Throwable
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->repository = $this->getContainer()->get(ItemRepositoryInterface::class);
    }
}
