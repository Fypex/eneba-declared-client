<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use DateTime;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareInterface;
use Fypex\EnebaClient\Denormalizer\DenormalizerAwareTrait;
use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Callbacks;
use Fypex\EnebaClient\Model\Sales;
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
