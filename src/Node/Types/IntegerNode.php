<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Node\Types;

use Wtsvk\SortedLinkedList\Node\AbstractNode;
use Wtsvk\SortedLinkedList\Node\Comparable;
use Wtsvk\SortedLinkedList\Node\NodeTypeException;

class IntegerNode extends AbstractNode
{
    public function __construct(int $data)
    {
        parent::__construct($data);
    }

    public function compareTo(Comparable $node): int
    {
        if (!$node instanceof self) {
            throw new NodeTypeException('Cannot compare different types of nodes');
        }

        return $this->data <=> $node->getData();
    }
}