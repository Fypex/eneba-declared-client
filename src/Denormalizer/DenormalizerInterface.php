<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer;

interface DenormalizerInterface
{
    public function denormalize($data, string $class);

    public function supportsDenormalization(string $class): bool;
}
