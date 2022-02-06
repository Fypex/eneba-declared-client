<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Factory\SelectionSet;

use Fypex\GraphqlQueryBuilder\SelectionSet\SelectionSet;

interface SelectionSetFactoryInterface
{
    public function get(): SelectionSet;
}
