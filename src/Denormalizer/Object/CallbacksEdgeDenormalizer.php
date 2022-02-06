<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Callbacks;
use Fypex\EnebaClient\Model\Relay\Edge\CallbackEdge;
use Fypex\EnebaClient\Model\Sales;

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
