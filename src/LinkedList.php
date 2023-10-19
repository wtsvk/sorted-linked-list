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

    public function __construct(?Node $head = null)
    {
        if ($head instanceof Node) {
            $this->insert($head);
        }
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
            $this->tail = $this->findTailNode();
            return;
        }

        $this->tail->setNext($node);
        $this->tail = $node;
    }

    public function split(Node $node): self
    {
        if ($node === $this->head) {
            throw new LinkedListException('Cannot split head node');
        }

        $this->tail = $node->getPrev();
        return new self($node->split());
    }

    public function join(self $linkedList): void
    {
        if ($this === $linkedList) {
            throw new LinkedListException('Cannot join linked list with itself');
        }

        foreach(iterator_to_array($linkedList) as $node) {
            $linkedList->remove($node);
            $this->insert($node);
        }
    }

    public function remove(Node $node): void
    {
        if ($node === $this->head) {
            $this->head = $node->getNext();
        }

        if ($node === $this->tail) {
            $this->tail = $node->getPrev();
        }

        $node->remove();
    }

    public function count(): int
    {
        return iterator_count($this->getIterator());
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

        foreach ($this->getIterator() as $node) {
            $result[] = $node->getData();
        }

        return $result;
    }

    protected function findTailNode(): ?Node
    {
        $nodes = iterator_to_array($this->getIterator());
        $last = end($nodes);

        return $last instanceof Node ? $last : null;
    }
}
