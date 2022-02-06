<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Factory\SelectionSet;

use Fypex\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum;
use Fypex\EnebaClient\Provider\SelectionSetFactoryProviderAwareInterface;
use Fypex\EnebaClient\Provider\SelectionSetFactoryProviderAwareTrait;
use Fypex\GraphqlQueryBuilder\SelectionSet\Field;
use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;

class CallbackSelectionSetFactory implements SelectionSetFactoryInterface, SelectionSetFactoryProviderAwareInterface
{
    use SelectionSetFactoryProviderAwareTrait;

    public function get(): SelectionSet
    {
        return new SelectionSet([
            new Field('success'),
        ]);
    }
}
