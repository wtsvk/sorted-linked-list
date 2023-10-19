<?php

declare(strict_types=1);

namespace Wtsvk\SortedLinkedList\Node;

interface Comparable extends Node
{
    public function compareTo(Comparable $node): int;
}
