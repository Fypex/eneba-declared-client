<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Model;

use DateTimeInterface;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

class Callbacks
{
    private $id;
    private $type;
    private $url;
    private $authorization;

    public function __construct(UuidInterface $id, $type, $url, $authorization) {

        $this->id = $id;
        $this->type = $type;
        $this->url = $url;
        $this->authorization = $authorization;

    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getType(): String
    {
        return $this->type;
    }

    public function getUrl(): String
    {
        return $this->url;
    }

    public function getAuthorization(): String
    {
        return $this->authorization;
    }
}
