<?php
/*
 * @author Ncode Alex
 * @link  https://github.com/ncodealex
 * @link https://ncode.su/
 * @copyright 2024.  Show Rent team
 */

namespace Tests\Unit\Share\NestedSet;

use Doctrine\Common\Collections\ArrayCollection;
use Shared\Domain\Utils\NestedSet\NestedSetService;
use Shared\Domain\Utils\NestedSet\NodeInterface;
use Tests\BaseTestCase;

class NestedSetServiceTest extends BaseTestCase
{
    /**
     * Test calculate nodes
     */
    public function test_calculate_nodes_light(): void
    {

        /*
         *   (1, 10, 0).      (2, 7, 1)      (3, 6, 2).   (4, 5, 3)
         *   root     |------> child ------> child3 ----> child4
         *                  (8, 9, 1)
         *            |------> child2
         *
         * */
        $root = $this->createRoot();

        $nodes = new ArrayCollection([$root]);
        NestedSetService::calculateNodes($nodes);
        $this->assertEquals(1, $root->getLeft(), 'Root left is not correct');
        $this->assertEquals(10, $root->getRight(), 'Root right is not correct');
    }

    /**
     * Create nodes map
     */
    public function createRoot(): NodeInterface
    {

        $root = new NodeMock();

        $child = new NodeMock();

        $child2 = new NodeMock();

        $child3 = new NodeMock();

        $child4 = new NodeMock();

        $child3->addChild($child4);
        $child->addChild($child3);

        $root->addChild($child);
        $root->addChild($child2);
        /*
         *   (1, 10, 0).      (2, 7, 1)      (3, 6, 2).   (4, 5, 3)
         *   root     |------> child ------> child3 ----> child4
         *                  (8, 9, 1)
         *            |------> child2
         *
         * */
        return $root;
    }

    /**
     * Test calculate nodes
     */
    public function test_calculate_nodes_hard(): void
    {

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
        $roots = $this->createRoots();
        NestedSetService::calculateNodes($roots);


        $this->assertEquals(1, $roots->get(0)->getLeft(), 'Root left is not correct');
        $this->assertEquals(24, $roots->get(0)->getRight(), 'Root right is not correct');

        $this->assertEquals(25, $roots->get(1)->getLeft(), 'Root left is not correct');
        $this->assertEquals(38, $roots->get(1)->getRight(), 'Root right is not correct');

        /// Check $child_3_1_1_1_1
        $child_3_1_1_1_1 = $roots->get(1)->getChildren()->get(2)->getChildren()->get(0)->getChildren()->get(0)->getChildren()->get(0);
        $this->assertEquals(33, $child_3_1_1_1_1->getLeft(), 'Root left is not correct');
        $this->assertEquals(34, $child_3_1_1_1_1->getRight(), 'Root right is not correct');
        $this->assertEquals(4, $child_3_1_1_1_1->getDepth(), 'Child_3_1_1_1_1 is not correct');
    }

    /**
     * Create nodes map
     *
     * @return ArrayCollection<array-key,NodeInterface>
     */
    public function createRoots(): ArrayCollection
    {

        // Root 1

        $root_1 = new NodeMock();

        $root_1_child_1_1 = new NodeMock();

        $root_1_child_1_2 = new NodeMock();

        $root_1_child_1_3 = new NodeMock();

        $root_1_child_1_2->addChild($root_1_child_1_3);
        $root_1_child_1_1->addChild($root_1_child_1_2);
        /// NODE 1
        $root_1->addChild($root_1_child_1_1);

        //// NODE 2
        $root_1_child_2_1 = new NodeMock();
        ///  NODE 2 -> Child 1
        $root_1_child_2_2 = new NodeMock();
        $root_1_child_2_3 = new NodeMock();
        ///  NODE 2 -> Child 2
        $root_1_child_2_2_1 = new NodeMock();
        $root_1_child_2_2_2 = new NodeMock();

        $root_1_child_2_2->addChild($root_1_child_2_3);
        $root_1_child_2_2_1->addChild($root_1_child_2_2_2);
        $root_1_child_2_1->addChild($root_1_child_2_2);
        $root_1_child_2_1->addChild($root_1_child_2_2_1);

        $root_1->addChild($root_1_child_2_1);


        //// NODE 3
        $root_1_child_3_1 = new NodeMock();

        $root_1_child_3_2 = new NodeMock();

        $root_1_child_3_3 = new NodeMock();

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

        $root_2 = new NodeMock();

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

        $root_2_child_1_1 = new NodeMock();

        $root_2_child_2_1 = new NodeMock();

        $root_2_child_3_1 = new NodeMock();

        $root_2_child_3_1_1 = new NodeMock();

        $root_2_child_3_1_1_1 = new NodeMock();

        $root_2_child_3_1_1_1_1 = new NodeMock();

        $root_2_child_3_1_1_1->addChild($root_2_child_3_1_1_1_1);

        $root_2_child_3_1_1->addChild($root_2_child_3_1_1_1);

        $root_2_child_3_1->addChild($root_2_child_3_1_1);
        $root_2->addChild($root_2_child_1_1);
        $root_2->addChild($root_2_child_2_1);
        $root_2->addChild($root_2_child_3_1);


        return new ArrayCollection([$root_1, $root_2]);
    }

    public function test_to_flat(): void
    {
        $roots = $this->createRoots();
        NestedSetService::calculateNodes($roots);
        $flat = NestedSetService::toFlat($roots);
        $this->assertEquals(19, $flat->count(), 'Flat count is not correct');
        $root_1 = $flat->get(0);
        $root_2 = $flat->get(12);
        $this->assertEquals(0, $root_1->getChildren()->count(), 'Root 1 children count is not correct');
        $this->assertEquals(0, $root_2->getChildren()->count(), 'Root 2 children count is not correct');
        $this->assertEquals(1, $root_1->getLeft(), 'Root 1 left is not correct');
        $this->assertEquals(24, $root_1->getRight(), 'Root 1 right is not correct');
        $this->assertEquals(25, $root_2->getLeft(), 'Root 2 left is not correct');
        $this->assertEquals(38, $root_2->getRight(), 'Root 2 right is not correct');
    }

    public function test_to_tree()
    {
        $roots = $this->createRoots();
        NestedSetService::calculateNodes($roots);
        $flat = NestedSetService::toFlat($roots);
        $tree = NestedSetService::toTree($flat);
        $this->assertEquals(2, $tree->count(), 'Tree count is not correct');
        $root_1 = $tree->get(0);
        $root_2 = $tree->get(1);
        $this->assertEquals(3, $root_1->getChildren()->count(), 'Root 1 children count is not correct');
        $this->assertEquals(3, $root_2->getChildren()->count(), 'Root 2 children count is not correct');
        $this->assertEquals(1, $root_1->getLeft(), 'Root 1 left is not correct');
        $this->assertEquals(24, $root_1->getRight(), 'Root 1 right is not correct');
        $this->assertEquals(25, $root_2->getLeft(), 'Root 2 left is not correct');
        $this->assertEquals(38, $root_2->getRight(), 'Root 2 right is not correct');
        
    }

}
