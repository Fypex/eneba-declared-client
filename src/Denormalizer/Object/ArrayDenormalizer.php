<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Denormalizer\Object;

use Helis\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Helis\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Helis\EnebaClient\Denormalizer\DenormalizerInterface;

class ArrayDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): array
    {
        $out = [];
        $baseClass = substr($class, 0, -2);
        foreach ($data as $item) {
            $out[] = $this->denormalizer->denormalize($item, $baseClass);
        }

        return $out;
    }

    public function supportsDenormalization(string $class): bool
    {
        if (substr($class, -2) === '[]') {
            return $this->denormalizer->supportsDenormalization(substr($class, 0, -2));
        }

        return false;
    }
}
