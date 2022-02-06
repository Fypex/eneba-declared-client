<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\ActionResponse;
use Fypex\EnebaClient\Model\CallbackResponse;
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
