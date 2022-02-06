<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer\Object;

use Fypex\EnebaClient\Denormalizer\DenormalizerInterface;
use Fypex\EnebaClient\Model\Relay\Connection\PageInfo;

class PageInfoDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $class): PageInfo
    {
        return new PageInfo(
            $data['startCursor'],
            $data['endCursor'],
            $data['hasPreviousPage'],
            $data['hasNextPage']
        );
    }

    public function supportsDenormalization(string $class): bool
    {
        return $class === PageInfo::class;
    }
}
