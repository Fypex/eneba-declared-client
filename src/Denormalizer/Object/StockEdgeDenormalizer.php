<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Relay\Edge\StockEdge;
use Fypex\EnebaClient\Model\Stock;

class StockEdgeDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): StockEdge
    {
        return new StockEdge(
            $data['cursor'],
            $this->denormalizer->denormalize($data['node'], Stock::class)
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === StockEdge::class;
    }
}
