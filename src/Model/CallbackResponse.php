<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Model;

class CallbackResponse
{
    private $success;

    public function __construct(bool $success)
    {
        $this->success = $success;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

}
