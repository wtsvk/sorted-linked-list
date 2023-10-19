<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Test\Node;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Wtsvk\SortedLinkedList\LinkedList;
use Wtsvk\SortedLinkedList\LinkedListException;
use Wtsvk\SortedLinkedList\Node\Node;
use Wtsvk\SortedLinkedList\Node\Types\StringNode;

final class LinkedListTest extends TestCase
{
    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testInsert(LinkedList $list, array $nodes): void
    {
        self::assertSame($nodes[0], $list->getHead());
        self::assertSame($nodes[3], $list->getTail());

        self::assertSame($nodes[1], $nodes[0]->getNext());
        self::assertSame($nodes[2], $nodes[1]->getNext());
        self::assertSame($nodes[3], $nodes[2]->getNext());
        self::assertNull($nodes[3]->getNext());

        self::assertSame($nodes[2], $nodes[3]->getPrev());
        self::assertSame($nodes[1], $nodes[2]->getPrev());
        self::assertSame($nodes[0], $nodes[1]->getPrev());
        self::assertNull($nodes[0]->getPrev());
    }

    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testIterate(LinkedList $list, array $nodes): void
    {
        self::assertSame($nodes, iterator_to_array($list));
    }

    #[DataProvider('provideLinkedList')]
    public function testJsonEncode(LinkedList $list): void
    {
        self::assertSame('["c","a","z","x"]', json_encode($list));
    }

    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testRemoveHead(LinkedList $list, array $nodes): void
    {
        $list->remove($nodes[0]);
        self::assertSame('["a","z","x"]', json_encode($list));
    }

    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testRemoveTail(LinkedList $list, array $nodes): void
    {
        $list->remove($nodes[3]);
        self::assertSame('["c","a","z"]', json_encode($list));
    }

    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testRemoveMiddleNode(LinkedList $list, array $nodes): void
    {
        $list->remove($nodes[1]);
        self::assertSame('["c","z","x"]', json_encode($list));
    }

    #[DataProvider('provideLinkedList')]
    public function testJoinLists(LinkedList $list): void
    {
        $list2 = new LinkedList();
        $list2->insert(new StringNode('0'));
        $list2->insert(new StringNode('d'));
        $list2->insert(new StringNode('zz'));
        $list->join($list2);

        self::assertSame('["c","a","z","x","0","d","zz"]', json_encode($list));
        self::assertSame('c', $list->getHead()?->getData());
        self::assertSame('zz', $list->getTail()?->getData());

        self::assertNull($list2->getHead());
        self::assertNull($list2->getTail());
    }

    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testSplitHead(LinkedList $list, array $nodes): void
    {
        $this->expectException(LinkedListException::class);
        $list->split($nodes[0]);
    }

    /**
     * @param Node[] $nodes
     */
    #[DataProvider('provideLinkedList')]
    public function testSplitTail(LinkedList $list, array $nodes): void
    {
        $list2 = $list->split($nodes[3]);
        self::assertSame('["c","a","z"]', json_encode($list));
        self::assertSame('["x"]', json_encode($list2));
    }

    /**
     * @return iterable<array{list: LinkedList, nodes: Node[]}>
     */
    public static function provideLinkedList(): iterable
    {
        $node1 = new StringNode('c');
        $node2 = new StringNode('a');
        $node3 = new StringNode('z');
        $node4 = new StringNode('x');

        $list = new LinkedList();
        $list->insert($node1);
        $list->insert($node2);
        $list->insert($node3);
        $list->insert($node4);

        yield [
            'list' => $list,
            'nodes' => [$node1, $node2, $node3, $node4],
        ];
    }
}