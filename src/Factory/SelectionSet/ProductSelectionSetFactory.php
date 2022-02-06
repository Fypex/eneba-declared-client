<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Factory\SelectionSet;

use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;
use Helis\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum;
use Helis\EnebaClient\Provider\SelectionSetFactoryProviderAwareInterface;
use Helis\EnebaClient\Provider\SelectionSetFactoryProviderAwareTrait;
use Fypex\GraphqlQueryBuilder\Argument\VariableValue;
use Fypex\GraphqlQueryBuilder\SelectionSet\Field;

class ProductSelectionSetFactory implements SelectionSetFactoryInterface, SelectionSetFactoryProviderAwareInterface
{
    use SelectionSetFactoryProviderAwareTrait;

    private $includeAuctions;

    public function __construct()
    {

    }

    public function get(): SelectionSet
    {
        return new SelectionSet([
            new Field('id'),
            new Field('name'),
            new Field('slug'),
            new Field('platform', new SelectionSet([
                new Field('label'),
                new Field('value'),
            ])),
            new Field('releasedAt'),
        ]);
    }
}
