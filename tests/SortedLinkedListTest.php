<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Test\Node;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Wtsvk\SortedLinkedList\LinkedListException;
use Wtsvk\SortedLinkedList\Node\Node;
use Wtsvk\SortedLinkedList\SortedLinkedList;
use Wtsvk\SortedLinkedList\Node\Types\StringNode;

final class SortedLinkedListTest extends TestCase
{
    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testInsert(SortedLinkedList $list, array $sortedNodes): void
    {
        self::assertSame($sortedNodes[0], $list->getHead());
        self::assertSame($sortedNodes[3], $list->getTail());

        self::assertSame($sortedNodes[1], $sortedNodes[0]->getNext());
        self::assertSame($sortedNodes[2], $sortedNodes[1]->getNext());
        self::assertSame($sortedNodes[3], $sortedNodes[2]->getNext());
        self::assertNull($sortedNodes[3]->getNext());

        self::assertSame($sortedNodes[2], $sortedNodes[3]->getPrev());
        self::assertSame($sortedNodes[1], $sortedNodes[2]->getPrev());
        self::assertSame($sortedNodes[0], $sortedNodes[1]->getPrev());
        self::assertNull($sortedNodes[0]->getPrev());
    }

    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testIterate(SortedLinkedList $list, array $sortedNodes): void
    {
        self::assertSame($sortedNodes, iterator_to_array($list));
    }

    #[DataProvider('provideLinkedList')]
    public function testJsonEncode(SortedLinkedList $list): void
    {
        self::assertSame('["a","c","x","z"]', json_encode($list));
    }

    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testRemoveHead(SortedLinkedList $list, array $sortedNodes): void
    {
        $list->remove($sortedNodes[0]);
        self::assertSame('["c","x","z"]', json_encode($list));
    }

    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testRemoveTail(SortedLinkedList $list, array $sortedNodes): void
    {
        $list->remove($sortedNodes[3]);
        self::assertSame('["a","c","x"]', json_encode($list));
    }

    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testRemoveMiddleNode(SortedLinkedList $list, array $sortedNodes): void
    {
        $list->remove($sortedNodes[1]);
        self::assertSame('["a","x","z"]', json_encode($list));
    }

    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testSplitHead(SortedLinkedList $list, array $sortedNodes): void
    {
        $this->expectException(LinkedListException::class);
        $list->split($sortedNodes[0]);
    }

    /**
     * @param Node[] $sortedNodes
     */
    #[DataProvider('provideLinkedList')]
    public function testSplitTail(SortedLinkedList $list, array $sortedNodes): void
    {
        $list2 = $list->split($sortedNodes[3]);
        self::assertSame('["a","c","x"]', json_encode($list));
        self::assertSame('["z"]', json_encode($list2));
    }

    #[DataProvider('provideLinkedList')]
    public function testJoinLists(SortedLinkedList $list): void
    {
        $list2 = new SortedLinkedList();
        $list2->insert(new StringNode('0'));
        $list2->insert(new StringNode('d'));
        $list2->insert(new StringNode('zz'));
        $list->join($list2);

        self::assertSame('["0","a","c","d","x","z","zz"]', json_encode($list));
        self::assertSame('0', $list->getHead()?->getData());
        self::assertSame('zz', $list->getTail()?->getData());

        self::assertNull($list2->getHead());
        self::assertNull($list2->getTail());
    }

    /**
     * @return iterable<array{list: SortedLinkedList, sortedNodes: Node[]}>
     */
    public static function provideLinkedList(): iterable
    {
        $node1 = new StringNode('c');
        $node2 = new StringNode('a');
        $node3 = new StringNode('z');
        $node4 = new StringNode('x');

        $list = new SortedLinkedList();
        $list->insert($node1);
        $list->insert($node2);
        $list->insert($node3);
        $list->insert($node4);

        yield [
            'list' => $list,
            'sortedNodes' => [$node2, $node1, $node4, $node3],
        ];
    }
}