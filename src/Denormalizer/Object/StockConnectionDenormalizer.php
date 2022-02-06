<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Relay\Connection\PageInfo;
use Fypex\EnebaClient\Model\Relay\Connection\StockConnection;
use Fypex\EnebaClient\Model\Relay\Edge\StockEdge;

class StockConnectionDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): StockConnection
    {
        return new StockConnection(
            $this->denormalizer->denormalize($data['edges'], StockEdge::class . '[]'),
            $this->denormalizer->denormalize($data['pageInfo'], PageInfo::class),
            $data['totalCount']
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === StockConnection::class;
    }
}
