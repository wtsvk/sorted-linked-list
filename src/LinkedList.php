<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList;

use IteratorAggregate;
use JsonSerializable;
use Traversable;
use Wtsvk\SortedLinkedList\Node\Node;

/**
 * @implements IteratorAggregate<Node>
 */
class LinkedList implements IteratorAggregate, JsonSerializable
{
    protected ?Node $head = null;
    protected ?Node $tail = null;

    public function __construct()
    {
    }

    public function getHead(): ?Node
    {
        return $this->head;
    }

    public function getTail(): ?Node
    {
        return $this->tail;
    }

    public function insert(Node $node): void
    {
        if ($this->head === null || $this->tail === null) {
            $this->head = $node;
            $this->tail = $node;
            return;
        }

        $this->tail->setNext($node);
        $this->tail = $node;
    }

    public function getIterator(): Traversable
    {
        $current = $this->head;

        while ($current instanceof Node) {
            yield $current;
            $current = $current->getNext();
        }
    }

    /**
     * @return mixed[]
     */
    public function jsonSerialize(): array
    {
        $result = [];

        foreach ($this as $node) {
            $result[] = $node->getData();
        }

        return $result;
    }
}
