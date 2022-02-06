<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Factory\SelectionSet;

use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;
use Fypex\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum;
use Fypex\EnebaClient\Provider\SelectionSetFactoryProviderAwareInterface;
use Fypex\EnebaClient\Provider\SelectionSetFactoryProviderAwareTrait;
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
