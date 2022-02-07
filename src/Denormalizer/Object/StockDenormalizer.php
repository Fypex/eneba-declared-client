<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use DateTime;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\PriceUpdateQuota;
use Fypex\EnebaClient\Model\Product;
use Fypex\EnebaClient\Model\Stock;
use Money\Money;
use Ramsey\Uuid\Uuid;

class StockDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;

    public function denormalize($data, string $class): Stock
    {

        $stock = new Stock(
            Uuid::fromString($data['id']),
            $this->denormalizer->denormalize($data['product'], Product::class),
            $data['unitsSold'],
            $data['onHand'],
            $data['declaredStock'],
            $data['status'],
            new DateTime($data['expiresAt']),
            $data['autoRenew'],
            $this->denormalizer->denormalize($data['price'], Money::class),
            new DateTime($data['createdAt'])
        );
        isset($data['priceUpdateQuota']) && $stock->setPriceUpdateQuota(
            $this->denormalizer->denormalize($data['priceUpdateQuota'], PriceUpdateQuota::class)
        );

        return $stock;
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === Stock::class;
    }
}
