<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\ActionResponse;
use Ramsey\Uuid\Uuid;

class ActionResponseDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $class): ActionResponse
    {
        return new ActionResponse(
            $data['isSuccessful'],
            isset($data['actionId']) ? Uuid::fromString($data['actionId']) : null
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === ActionResponse::class;
    }
}
