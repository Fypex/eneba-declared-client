<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Provider;

interface SelectionSetFactoryProviderAwareInterface
{
    public function setSelectionSetFactoryProvider(SelectionSetFactoryProviderInterface $provider): void;
}
