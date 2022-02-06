<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Factory\SelectionSet;

use Fypex\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum;
use Fypex\EnebaClient\Provider\SelectionSetFactoryProviderAwareInterface;
use Fypex\EnebaClient\Provider\SelectionSetFactoryProviderAwareTrait;
use Fypex\GraphqlQueryBuilder\SelectionSet\Field;
use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;

class ConnectionSelectionSetFactory implements SelectionSetFactoryInterface, SelectionSetFactoryProviderAwareInterface
{
    use SelectionSetFactoryProviderAwareTrait;

    private $nodeNameEnum;

    public function __construct(SelectionSetFactoryProviderNameEnum $nodeNameEnum)
    {
        $this->nodeNameEnum = $nodeNameEnum;
    }

    public function get(): SelectionSet
    {
        return new SelectionSet([
            new Field('totalCount'),
            new Field('pageInfo', new SelectionSet([
                new Field('hasNextPage'),
                new Field('hasPreviousPage'),
                new Field('startCursor'),
                new Field('endCursor'),
            ])),
            new Field('edges', new SelectionSet([
                new Field('node', $this->provider->get($this->nodeNameEnum)->get()),
                new Field('cursor'),
            ])),
        ]);
    }
}
