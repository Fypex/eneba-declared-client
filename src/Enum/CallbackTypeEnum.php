<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Enum;

use MyCLabs\Enum\Enum;

/**
 * @method static static DECLARED_STOCK_RESERVATION()
 * @method static static DECLARED_STOCK_PROVISION()
 * @method static static DECLARED_STOCK_CANCELLATION()
 */
class CallbackTypeEnum extends Enum
{
    public const DECLARED_STOCK_RESERVATION = 'DECLARED_STOCK_RESERVATION';
    public const DECLARED_STOCK_PROVISION = 'DECLARED_STOCK_PROVISION';
    public const DECLARED_STOCK_CANCELLATION = 'DECLARED_STOCK_CANCELLATION';
}
