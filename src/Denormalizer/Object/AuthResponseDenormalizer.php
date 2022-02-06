<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\AccessToken;

class AuthResponseDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $class): AccessToken
    {
        return new AccessToken(
            $data['access_token'],
            $data['expires_in']
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === AccessToken::class;
    }
}
