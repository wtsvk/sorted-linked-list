<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Node;

abstract class AbstractNode implements Comparable
{
    private ?Node $prev = null;
    private ?Node $next = null;

    private static int $recursion = 0;

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

    public function setPrev(?Node $node): void
    {
        if ($node === $this->prev) {
            return;
        }

        $this->validateNode($node, self::$recursion++);

        if ($this->prev !== null) {
            $node?->setPrev($this->prev);
        }

        $this->prev = $node;
        $node?->setNext($this);
        self::$recursion--;
    }

    public function setNext(?Node $node): void
    {
        if ($node === $this->next) {
            return;
        }

        $this->validateNode($node, self::$recursion++);

        if ($this->next !== null) {
            $node?->setNext($this->next);
        }

        $this->next = $node;
        $node?->setPrev($this);
        self::$recursion--;
    }

    public function getData(): mixed
    {
        return $this->data;
    }

    public function remove(): self
    {
        self::$recursion++;

        $this->prev?->setNext(null);
        $this->next?->setPrev(null);

        $this->prev?->setNext($this->next);
        $this->next?->setPrev($this->prev);

        $this->prev = null;
        $this->next = null;

        self::$recursion--;
        return $this;
    }

    public function split(): self
    {
        self::$recursion++;

        $this->prev?->setNext(null);
        $this->prev = null;

        self::$recursion--;
        return $this;
    }

    public function isPartOfList(): bool
    {
        return $this->prev !== null || $this->next !== null;
    }

    private function validateNode(?Node $node, int $recursion): void
    {
        if ($node === null) {
            if ($recursion === 0) {
                self::$recursion = 0;
                throw new NodeLogicException('Cannot set next/prev node to null directly. Use remove() method instead.');
            }

            return;
        }

        if ( !($node instanceof $this)) {
            self::$recursion = 0;
            throw new NodeTypeException('Cannot set different types of nodes in the same list');
        }

        if ($node === $this) {
            self::$recursion = 0;
            throw new NodeLogicException('The Node cannot reference itself');
        }

        if ($recursion === 0 && $node->isPartOfList()) {
            self::$recursion = 0;
            throw new NodeLogicException('The node you are trying to add is already part of any list');
        }
    }
}
