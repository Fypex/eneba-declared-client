<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static static CALLBACKS()
 * @method static static CALLBACK()
 * @method static static STOCK_CONNECTION()
 * @method static static STOCK()
 * @method static static PRODUCT()
 * @method static static MONEY()
 * @method static static PRICE_UPDATE_QUOTA()
 * @method static static ACTION_RESPONSE()
 * @method static static FRAGMENT_DECLARED_STOCK()
 */
class SelectionSetFactoryProviderNameEnum extends Enum
{
    public const ACTION_RESPONSE = 'actionResponse';
    public const CALLBACKS = 'callbacks';
    public const CALLBACK = 'callback';
    public const STOCK_CONNECTION = 'stockConnection';
    public const STOCK = 'stock';
    public const PRODUCT = 'product';
    public const MONEY = 'money';
    public const PRICE_UPDATE_QUOTA = 'priceUpdateQuota';
    public const FRAGMENT_DECLARED_STOCK = 'declaredStock';

}
