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
            $this->tail = $node;
            return;
        }

        $current = $this->head;

        while ($current instanceof ComparableNode) {
            if ($current->compareTo($node) < 0) {
                $current->setNext($node);

                if ($node->getPrev() === null) {
                    $this->head = $node;
                }

                if ($node->getNext() === null) {
                    $this->tail = $node;
                }

                return;
            }

            $current = $current->getNext();
        }
    }
}
