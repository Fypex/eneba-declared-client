<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Denormalizer\Object;

use Helis\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Helis\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Helis\EnebaClient\Denormalizer\DenormalizerInterface;
use Helis\EnebaClient\Model\Relay\Connection\CallbacksConnection;
use Helis\EnebaClient\Model\Relay\Connection\PageInfo;
use Helis\EnebaClient\Model\Relay\Connection\SalesCallback;
use Helis\EnebaClient\Model\Relay\Edge\CallbackEdge;
use Helis\EnebaClient\Model\Relay\Edge\SalesEdge;

class CallbacksConnectionDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): CallbacksConnection
    {

        return new CallbacksConnection(
            $this->denormalizer->denormalize($data, CallbackEdge::class),
            $this->denormalizer->denormalize([
                'endCursor' => null,
                'startCursor' => null,
                'hasPreviousPage' => false,
                'hasNextPage' => false,
            ], PageInfo::class),
            0
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === CallbacksConnection::class;
    }
}
