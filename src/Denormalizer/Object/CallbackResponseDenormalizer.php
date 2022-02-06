<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Denormalizer\Object;

use Helis\EnebaClient\Denormalizer\DenormalizerInterface;
use Helis\EnebaClient\Model\ActionResponse;
use Helis\EnebaClient\Model\CallbackResponse;
use Ramsey\Uuid\Uuid;

class CallbackResponseDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $class): CallbackResponse
    {
        return new CallbackResponse(
            $data['success'],
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === CallbackResponse::class;
    }
}
