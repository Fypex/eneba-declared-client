<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer;

trait DenormalizerAwareTrait
{
    /**
     * @var DenormalizerInterface
     */
    protected $denormalizer;

    public function setDenormalizer(DenormalizerInterface $denormalizer): void
    {
        $this->denormalizer = $denormalizer;
    }
}
