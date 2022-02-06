<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Provider;

use Helis\EnebaClient\Enum\SelectionSetFactoryProviderNameEnum as ProviderNameEnum;
use Helis\EnebaClient\Factory\SelectionSet\ActionResponseSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\ActionSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\AuctionSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\CallbackSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\CallbacksSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\ConnectionSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\CountFeeSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\FragmentDeclaredStockSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\KeySelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\MoneySelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\PriceUpdateQuotaSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\ProductSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\SalesSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\SelectionSetFactoryInterface;
use Helis\EnebaClient\Factory\SelectionSet\StockSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\TransactionsFragmentSelectionSetFactory;
use Helis\EnebaClient\Factory\SelectionSet\TransactionsSelectionSetFactory;
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
