<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Provider;

use Fypex\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum;
use Fypex\EnebaClient\Factory\SelectionSet\SelectionSetFactoryInterface;

interface SelectionSetFactoryProviderInterface
{
    public function get(SelectionSetFactoryProviderNameEnum $name): SelectionSetFactoryInterface;
}
