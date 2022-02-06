<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use DateTime;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Product;
use Fypex\EnebaClient\Model\Relay\Connection\AuctionConnection;
use Ramsey\Uuid\Uuid;

class ProductDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): Product
    {
        $product = new Product(
            Uuid::fromString($data['id']),
            $data['name'],
            $data['slug'],
            $data['platform']['label'],
            [],
            isset($data['releasedAt']) ? new DateTime($data['releasedAt']) : null
        );

        if (isset($data['auctions'])) {
            $product->setAuctions(
                $this->denormalizer->denormalize($data['auctions'], AuctionConnection::class)
            );
        }

        return $product;
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === Product::class;
    }
}
