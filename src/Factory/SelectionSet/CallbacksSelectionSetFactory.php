<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Factory\SelectionSet;

use Helis\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum;
use Helis\EnebaClient\Provider\SelectionSetFactoryProviderAwareInterface;
use Helis\EnebaClient\Provider\SelectionSetFactoryProviderAwareTrait;
use Fypex\GraphqlQueryBuilder\SelectionSet\Field;
use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;

class CallbacksSelectionSetFactory implements SelectionSetFactoryInterface, SelectionSetFactoryProviderAwareInterface
{
    use SelectionSetFactoryProviderAwareTrait;

    public function get(): SelectionSet
    {
        return new SelectionSet([
            new Field('id'),
            new Field('type'),
            new Field('url'),
            new Field('authorization'),
        ]);
    }
}
