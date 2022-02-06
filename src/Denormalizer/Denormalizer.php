<?php
declare(strict_types=1);

namespace Helis\EnebaClient\Denormalizer;

use Helis\EnebaClient\Denormalizer\Object\ActionResponseDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\ActionStateDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\ArrayDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\AuctionConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\AuctionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\AuctionEdgeDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\AuthResponseDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\CallbackResponseDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\CallbacksConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\CallbacksDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\CallbacksEdgeDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\KeyConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\KeyDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\KeyEdgeDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\MoneyDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\PageInfoDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\PriceUpdateQuotaDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\ProductConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\ProductDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\ProductEdgeDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\SalesConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\SalesDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\SalesEdgeDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\StockConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\StockDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\StockEdgeDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\TransactionsConnectionDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\TransactionsDenormalizer;
use Helis\EnebaClient\Denormalizer\Object\TransactionsEdgeDenormalizer;
use Helis\EnebaClient\Model\Relay\Edge\TransactionsEdge;
use RuntimeException;

class Denormalizer implements DenormalizerInterface
{
    /**
     * @var self
     */
    private static $instance;

    /**
     * @var DenormalizerInterface[]
     */
    private $denormalizers;

    private $cache = [];

    private function __construct()
    {
        $this->denormalizers = [
            new ArrayDenormalizer(),
            new PageInfoDenormalizer(),
            new CallbackResponseDenormalizer(),
            new AuthResponseDenormalizer(),
            new CallbacksConnectionDenormalizer(),
            new CallbacksEdgeDenormalizer(),
            new CallbacksDenormalizer(),
            new StockDenormalizer(),
            new StockEdgeDenormalizer(),
            new StockConnectionDenormalizer(),
            new ProductDenormalizer(),
            new ProductEdgeDenormalizer(),
            new ProductConnectionDenormalizer(),
            new MoneyDenormalizer(),
            new PriceUpdateQuotaDenormalizer(),
            new ActionResponseDenormalizer(),
        ];
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();

            foreach (self::$instance->denormalizers as $denormalizer) {
                if ($denormalizer instanceof DenormalizerAwareInterface) {
                    $denormalizer->setDenormalizer(self::$instance);
                }
            }
        }

        return self::$instance;
    }

    public function denormalize($data, string $class)
    {

        if ($data === null) {
            return null;
        }

        if (!$this->supportsDenormalization($class)) {
            throw new RuntimeException(sprintf('Denormalization of given class(%s) is not supported', $class));
        }

        return $this->cache[$class]->denormalize($data, $class);
    }

    public function supportsDenormalization(string $class): bool
    {

        if (isset($this->cache[$class])) {
            return $this->cache[$class] !== false;
        }

        foreach ($this->denormalizers as $denormalizer) {
            if ($denormalizer->supportsDenormalization($class)) {
                $this->cache[$class] = $denormalizer;

                return true;
            }
        }

        return $this->cache[$class] = false;
    }
}
