<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Node;

abstract class AbstractNode implements Comparable
{
    private ?Node $prev = null;
    private ?Node $next = null;

    public function __construct(public readonly mixed $data)
    {
    }

    public function getPrev(): ?Node
    {
        return $this->prev;
    }

    public function getNext(): ?Node
    {
        return $this->next;
    }

    public function setPrev(Node $node): void
    {
        if ($node === $this->prev) {
            return;
        }

        if ($node === $this) {
            throw new NodeLogicException('Node cannot reference itself');
        }

        if ( !($node instanceof $this)) {
            throw new NodeTypeException('Cannot set different types of nodes');
        }

        if ($this->prev !== null) {
            $node->setPrev($this->prev);
        }

        $this->prev = $node;
        $node->setNext($this);
    }

    public function setNext(Node $node): void
    {
        if ($node === $this->next) {
            return;
        }

        if ($node === $this) {
            throw new NodeLogicException('Node cannot reference itself');
        }

        if ( !($node instanceof $this)) {
            throw new NodeTypeException('Cannot set different types of nodes');
        }

        if ($this->next !== null) {
            $node->setNext($this->next);
        }

        $this->next = $node;
        $node->setPrev($this);
    }

    public function getData(): mixed
    {
        return $this->data;
    }
}
