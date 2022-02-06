<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Provider;

trait SelectionSetFactoryProviderAwareTrait
{
    /**
     * @var SelectionSetFactoryProviderInterface
     */
    protected $provider;

    public function setSelectionSetFactoryProvider(SelectionSetFactoryProviderInterface $provider): void
    {
        $this->provider = $provider;
    }
}
