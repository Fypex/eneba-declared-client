<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Storage;

use DateTime;

interface AccessTokenStorageInterface
{
    public function get(): ?string;

    public function set(string $accessToken, DateTime $expiresAt): void;
}
