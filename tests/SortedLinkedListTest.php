<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Test\Node;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Wtsvk\SortedLinkedList\Node\Node;
use Wtsvk\SortedLinkedList\SortedLinkedList;
use Wtsvk\SortedLinkedList\Node\Types\StringNode;

final class SortedLinkedListTest extends TestCase
{
    #[DataProvider('provideLinkedList')]
    public function testInsert(Node $node1, Node $node2, Node $node3, SortedLinkedList $list): void
    {
        self::assertSame($node1, $list->getHead());
        self::assertSame($node2, $list->getTail());

        self::assertSame($node3, $node1->getNext());
        self::assertSame($node2, $node3->getNext());
        self::assertNull($node2->getNext());

        self::assertSame($node3, $node2->getPrev());
        self::assertSame($node1, $node3->getPrev());
        self::assertNull($node1->getPrev());
    }

    #[DataProvider('provideLinkedList')]
    public function testIterate(Node $node1, Node $node2, Node $node3, SortedLinkedList $list): void
    {
        self::assertSame([$node1, $node3, $node2], iterator_to_array($list));
    }

    #[DataProvider('provideLinkedList')]
    public function testJsonEncode(Node $node1, Node $node2, Node $node3, SortedLinkedList $list): void
    {
        self::assertSame('["a","b","c"]', json_encode($list));
    }

    /**
     * @return iterable<array{node1: StringNode, node2: StringNode, node3: StringNode, list: SortedLinkedList}>
     */
    public static function provideLinkedList(): iterable
    {
        $node1 = new StringNode('a');
        $node2 = new StringNode('c');
        $node3 = new StringNode('b');

        $list = new SortedLinkedList();
        $list->insert($node1);
        $list->insert($node2);
        $list->insert($node3);

        yield [
            'node1' => $node1,
            'node2' => $node2,
            'node3' => $node3,
            'list' => $list,
        ];
    }
}