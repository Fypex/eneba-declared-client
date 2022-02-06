<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Relay\Connection\CallbacksConnection;
use Fypex\EnebaClient\Model\Relay\Connection\PageInfo;
use Fypex\EnebaClient\Model\Relay\Connection\SalesCallback;
use Fypex\EnebaClient\Model\Relay\Edge\CallbackEdge;
use Fypex\EnebaClient\Model\Relay\Edge\SalesEdge;

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
