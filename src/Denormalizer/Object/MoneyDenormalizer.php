<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Money\Currency;
use Money\Money;

class MoneyDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $class): Money
    {
        return new Money(
            $data['amount'],
            new Currency($data['currency'])
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === Money::class;
    }
}
