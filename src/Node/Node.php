<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Node;

interface Node
{
    public function getPrev(): ?Node;
    public function getNext(): ?Node;
    public function setPrev(?Node $node): void;
    public function setNext(?Node $node): void;
    public function getData(): mixed;
    public function isPartOfList(): bool;
    public function remove(): Node;
    public function split(): Node;
}
