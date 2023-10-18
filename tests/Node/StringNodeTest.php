<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Test\Node;

use PHPUnit\Framework\TestCase;
use Wtsvk\SortedLinkedList\Node\Types\StringNode;
use PHPUnit\Framework\Attributes\DataProvider;
use Wtsvk\SortedLinkedList\Node\AbstractNode;
use Wtsvk\SortedLinkedList\Node\NodeTypeException;

final class StringNodeTest extends TestCase
{
    #[DataProvider('provideNodesForComparation')]
    public function testNodesComparation(StringNode $node1, StringNode $node2, int $expectedResult): void
    {
        self::assertSame($expectedResult, $node1->compareTo($node2));
    }

    public function testDifferentTypesComparation(): void
    {
        $node1 = new StringNode('xxx');
        $node2 = $this->createMock(AbstractNode::class);

        $this->expectException(NodeTypeException::class);
        $node1->compareTo($node2);
    }

    public function testSetDifferentPrevNode(): void
    {
        $node1 = new StringNode('xxx');
        $node2 = $this->createMock(AbstractNode::class);

        $this->expectException(NodeTypeException::class);
        $node1->setPrev($node2);
    }

    public function testSetDifferentNextNode(): void
    {
        $node1 = new StringNode('xxx');
        $node2 = $this->createMock(AbstractNode::class);

        $this->expectException(NodeTypeException::class);
        $node1->setNext($node2);
    }

    public function testSetPrev(): void
    {
        $node1 = new StringNode('xxx');
        $node2 = new StringNode('yyy');
        $node3 = new StringNode('zzz');
        $node4 = new StringNode('000');

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
        $node1 = new StringNode('xxx');
        $node2 = new StringNode('yyy');
        $node3 = new StringNode('zzz');
        $node4 = new StringNode('000');

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
     * @return iterable<array{node1: StringNode, node2: StringNode, expectedResult: int}>
     */
    public static function provideNodesForComparation(): iterable
    {
        yield 'node1 < node2' => [
            'node1' => new StringNode('a'),
            'node2' => new StringNode('b'),
            'expectedResult' => -1,
        ];

        yield 'node1 > node2' => [
            'node1' => new StringNode('b'),
            'node2' => new StringNode('a'),
            'expectedResult' => 1,
        ];

        yield 'node1 = node2' => [
            'node1' => new StringNode('c'),
            'node2' => new StringNode('c'),
            'expectedResult' => 0,
        ];
    }
}