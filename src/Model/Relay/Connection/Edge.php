<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Model\Relay\Connection;

class Edge
{
    protected $cursor;
    protected $node;

    /**
     * @param mixed $node
     */
    public function __construct(string $cursor, $node)
    {
        $this->cursor = $cursor;
        $this->node = $node;
    }

    public function getCursor(): string
    {
        return $this->cursor;
    }

    public function getNode()
    {
        return $this->node;
    }
}
