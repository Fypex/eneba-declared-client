<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Provider;

use Fypex\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum as ProviderNameEnum;
use Fypex\EnebaClient\Factory\SelectionSet\ActionResponseSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\ActionSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\AuctionSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\CallbackSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\CallbacksSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\ConnectionSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\CountFeeSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\FragmentDeclaredStockSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\KeySelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\MoneySelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\PriceUpdateQuotaSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\ProductSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\SalesSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\SelectionSetFactoryInterface;
use Fypex\EnebaClient\Factory\SelectionSet\StockSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\TransactionsFragmentSelectionSetFactory;
use Fypex\EnebaClient\Factory\SelectionSet\TransactionsSelectionSetFactory;
use RuntimeException;

class SelectionSetFactoryProvider implements SelectionSetFactoryProviderInterface
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var SelectionSetFactoryInterface[]
     */
    private $factories;

    private function __construct()
    {
        $this->factories = [
            ProviderNameEnum::ACTION_RESPONSE => new ActionResponseSelectionSetFactory(),
            ProviderNameEnum::CALLBACK => new CallbackSelectionSetFactory(),
            ProviderNameEnum::CALLBACKS => new CallbacksSelectionSetFactory(),
            ProviderNameEnum::STOCK => new StockSelectionSetFactory(),
            ProviderNameEnum::STOCK_CONNECTION => new ConnectionSelectionSetFactory(ProviderNameEnum::STOCK()),
            ProviderNameEnum::PRODUCT => new ProductSelectionSetFactory(),
            ProviderNameEnum::MONEY => new MoneySelectionSetFactory(),
            ProviderNameEnum::PRICE_UPDATE_QUOTA => new PriceUpdateQuotaSelectionSetFactory(),
        ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();

            foreach (self::$instance->factories as $factory) {
                if ($factory instanceof SelectionSetFactoryProviderAwareInterface) {
                    $factory->setSelectionSetFactoryProvider(self::$instance);
                }
            }
        }

        return self::$instance;
    }

    public function get(ProviderNameEnum $name): SelectionSetFactoryInterface
    {

        if (!isset($this->factories[$name->getValue()])) {
            throw new RuntimeException('Requested SelectionSetFactory cannot be found! ('.$name->getValue().')');
        }

        return $this->factories[$name->getValue()];
    }
}
