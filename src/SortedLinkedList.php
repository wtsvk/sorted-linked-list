<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList;

use Wtsvk\SortedLinkedList\Node\Comparable as ComparableNode;
use Wtsvk\SortedLinkedList\Node\Node;

class SortedLinkedList extends LinkedList
{
    public function insert(Node $node): void
    {
        if ( !($node instanceof ComparableNode)) {
            throw new SortedLinkedListException('Only comparable nodes could be used inside sorted linked list');
        }

        if ($this->head === null || $this->tail === null) {
            $this->head = $node;
            $this->tail = $this->findTailNode();
            return;
        }

        $current = $this->head;

        while ($current instanceof ComparableNode) {
            if ($node->compareTo($current) < 0) {
                $current->setPrev($node);

                if ($node->getPrev() === null) {
                    $this->head = $node;
                }

                return;
            }

            $current = $current->getNext();
        }

        $this->tail->setNext($node);
        $this->tail = $node;
    }
}
