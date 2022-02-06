<?php
declare(strict_types=1);

namespace Helis\EnebaClient;

use Helis\EnebaClient\Enum\FeeTypeEnum;
use Helis\EnebaClient\Model\ActionResponse;
use Helis\EnebaClient\Model\ActionState;
use Helis\EnebaClient\Model\Input\KeysFilter;
use Helis\EnebaClient\Model\Input\ProductsFilter;
use Helis\EnebaClient\Model\Input\SalesFilter;
use Helis\EnebaClient\Model\Input\StockFilter;
use Helis\EnebaClient\Model\Product;
use Helis\EnebaClient\Model\Relay\Connection\KeyCallback;
use Helis\EnebaClient\Model\Relay\Connection\ProductCallback;
use Helis\EnebaClient\Model\Relay\Connection\SalesCallback;
use Helis\EnebaClient\Model\Relay\Connection\StockCallback;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

interface ClientInterface
{

}
