<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Denormalizer\Object;

use Helis\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Helis\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Helis\EnebaClient\Denormalizer\DenormalizerInterface;
use Helis\EnebaClient\Model\Callbacks;
use Helis\EnebaClient\Model\Relay\Edge\CallbackEdge;
use Helis\EnebaClient\Model\Sales;

class CallbacksEdgeDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): CallbackEdge
    {

        return new CallbackEdge(
            '',
            $this->denormalizer->denormalize($data, Callbacks::class)
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === CallbackEdge::class;
    }
}
