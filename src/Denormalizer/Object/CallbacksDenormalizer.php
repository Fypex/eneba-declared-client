<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Denormalizer\Object;

use DateTime;
use Helis\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Helis\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Helis\EnebaClient\Denormalizer\DenormalizerInterface;
use Helis\EnebaClient\Model\Callbacks;
use Helis\EnebaClient\Model\Sales;
use Money\Money;
use Ramsey\Uuid\Uuid;

class CallbacksDenormalizer implements DenormalizerInterface, DenormalizerAwareInterface
{
    use DenormalizerAwareTrait;


    /**
     *
     * @param $data
     * @param string $class
     * @return array
     */
    public function denormalize($array, string $class)
    {
        $callbacks = [];
        foreach ($array as $data) {
            $callbacks[] = new Callbacks(
                Uuid::fromString($data['id']),
                $data['type'],
                $data['url'],
                $data['authorization'],
            );
        }

        return $callbacks;

    }


    public function supportsDenormalization(string $class): bool
    {
        return $class === Callbacks::class;
    }
}
