<?php
declare(strict_types=1);

namespace Fypex\EnebaClient;

use Fypex\EnebaClient\Enum\FeeTypeEnum;
use Fypex\EnebaClient\Model\ActionResponse;
use Fypex\EnebaClient\Model\ActionState;
use Fypex\EnebaClient\Model\Input\KeysFilter;
use Fypex\EnebaClient\Model\Input\ProductsFilter;
use Fypex\EnebaClient\Model\Input\SalesFilter;
use Fypex\EnebaClient\Model\Input\StockFilter;
use Fypex\EnebaClient\Model\Product;
use Fypex\EnebaClient\Model\Relay\Connection\KeyCallback;
use Fypex\EnebaClient\Model\Relay\Connection\ProductCallback;
use Fypex\EnebaClient\Model\Relay\Connection\SalesCallback;
use Fypex\EnebaClient\Model\Relay\Connection\StockCallback;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

interface ClientInterface
{

}
