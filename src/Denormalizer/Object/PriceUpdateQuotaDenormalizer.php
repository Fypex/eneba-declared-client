<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\PriceUpdateQuota;

class PriceUpdateQuotaDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $class): PriceUpdateQuota
    {
        return new PriceUpdateQuota(
            $data['quota'],
            $data['totalFree'],
            $data['nextFreeIn']
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === PriceUpdateQuota::class;
    }
}
