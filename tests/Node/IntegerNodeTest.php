<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Test\Node;

use PHPUnit\Framework\TestCase;
use Wtsvk\SortedLinkedList\Node\Types\IntegerNode;
use PHPUnit\Framework\Attributes\DataProvider;
use Wtsvk\SortedLinkedList\Node\AbstractNode;
use Wtsvk\SortedLinkedList\Node\NodeLogicException;
use Wtsvk\SortedLinkedList\Node\NodeTypeException;

final class IntegerNodeTest extends TestCase
{
    #[DataProvider('provideNodesForComparation')]
    public function testNodesComparation(IntegerNode $node1, IntegerNode $node2, int $expectedResult): void
    {
        self::assertSame($expectedResult, $node1->compareTo($node2));
    }

    public function testDifferentTypesComparation(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = $this->createMock(AbstractNode::class);

        $this->expectException(NodeTypeException::class);
        $node1->compareTo($node2);
    }

    public function testSetDifferentTypePrevNode(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = $this->createMock(AbstractNode::class);

        $this->expectException(NodeTypeException::class);
        $node1->setPrev($node2);
    }

    public function testSetDifferentTypeNextNode(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = $this->createMock(AbstractNode::class);

        $this->expectException(NodeTypeException::class);
        $node1->setNext($node2);
    }

    public function testSetPrevSameNode(): void
    {
        $node = new IntegerNode(1);

        $this->expectException(NodeLogicException::class);
        $node->setPrev($node);
    }

    public function testSetNextSameNode(): void
    {
        $node = new IntegerNode(1);

        $this->expectException(NodeLogicException::class);
        $node->setNext($node);
    }

    public function testSetPrevNull(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);

        $node1->setPrev($node2);
        $this->expectException(NodeLogicException::class);
        $node1->setPrev(null);
    }

    public function testSetNextNull(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);

        $node1->setNext($node2);
        $this->expectException(NodeLogicException::class);
        $node1->setNext(null);
    }

    public function testSetPrevRecursive(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);

        $node1->setPrev($node2);

        $this->expectException(NodeLogicException::class);
        $node2->setPrev($node1);
    }

    public function testSetNextRecursive(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);

        $node1->setNext($node2);

        $this->expectException(NodeLogicException::class);
        $node2->setNext($node1);
    }

    public function testRemove(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);
        $node3 = new IntegerNode(3);
        $node4 = new IntegerNode(4);

        $node1->setNext($node2);
        $node2->setNext($node3);
        $node3->setNext($node4);

        self::assertTrue($node2->isPartOfList());
        $node2->remove();

        self::assertFalse($node2->isPartOfList());
        self::assertSame($node3, $node1->getNext());
        self::assertSame($node1, $node3->getPrev());

        self::assertTrue($node1->isPartOfList());
        $node1->remove();

        self::assertFalse($node1->isPartOfList());
        self::assertTrue($node3->isPartOfList());
        self::assertNull($node3->getPrev());

        $node4->remove();
        self::assertFalse($node3->isPartOfList());
        self::assertFalse($node4->isPartOfList());
    }

    public function testSplit(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);
        $node3 = new IntegerNode(3);
        $node4 = new IntegerNode(4);

        $node1->setNext($node2);
        $node2->setNext($node3);
        $node3->setNext($node4);

        self::assertSame($node3, $node2->getNext());
        self::assertSame($node2, $node3->getPrev());
        $node3->split();

        self::assertSame($node2, $node1->getNext());
        self::assertSame($node1, $node2->getPrev());
        self::assertNull($node2->getNext());

       self::assertSame($node4, $node3->getNext());
       self::assertSame($node3, $node4->getPrev());
       self::assertNull($node3->getPrev());
    }

    public function testSetPrev(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);
        $node3 = new IntegerNode(3);
        $node4 = new IntegerNode(4);

        $node4->setPrev($node2);
        $node4->setPrev($node3);
        $node2->setPrev($node1);

        self::assertSame($node2, $node1->getNext());
        self::assertSame($node3, $node2->getNext());
        self::assertSame($node4, $node3->getNext());
        self::assertNull($node4->getNext());

        self::assertSame($node3, $node4->getPrev());
        self::assertSame($node2, $node3->getPrev());
        self::assertSame($node1, $node2->getPrev());
        self::assertNull($node1->getPrev());
    }

    public function testSetNext(): void
    {
        $node1 = new IntegerNode(1);
        $node2 = new IntegerNode(2);
        $node3 = new IntegerNode(3);
        $node4 = new IntegerNode(4);

        $node1->setNext($node3);
        $node1->setNext($node2);
        $node3->setNext($node4);

        self::assertSame($node2, $node1->getNext());
        self::assertSame($node3, $node2->getNext());
        self::assertSame($node4, $node3->getNext());
        self::assertNull($node4->getNext());

        self::assertSame($node3, $node4->getPrev());
        self::assertSame($node2, $node3->getPrev());
        self::assertSame($node1, $node2->getPrev());
        self::assertNull($node1->getPrev());
    }

    /**
     * @return iterable<array{node1: IntegerNode, node2: IntegerNode, expectedResult: int}>
     */
    public static function provideNodesForComparation(): iterable
    {
        yield 'node1 < node2' => [
            'node1' => new IntegerNode(1),
            'node2' => new IntegerNode(2),
            'expectedResult' => -1,
        ];

        yield 'node1 > node2' => [
            'node1' => new IntegerNode(2),
            'node2' => new IntegerNode(1),
            'expectedResult' => 1,
        ];

        yield 'node1 = node2' => [
            'node1' => new IntegerNode(1),
            'node2' => new IntegerNode(1),
            'expectedResult' => 0,
        ];
    }
}