<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Product;
use Fypex\EnebaClient\Model\Relay\Edge\ProductEdge;

class ProductEdgeDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): ProductEdge
    {
        return new ProductEdge(
            $data['cursor'],
            $this->denormalizer->denormalize($data['node'], Product::class)
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === ProductEdge::class;
    }
}
