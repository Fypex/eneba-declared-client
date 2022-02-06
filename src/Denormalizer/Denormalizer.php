<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Denormalizer;

use Fypex\EnebaClient\Denormalizer\Object\ActionResponseDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\ActionStateDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\ArrayDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\AuctionConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\AuctionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\AuctionEdgeDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\AuthResponseDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\CallbackResponseDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\CallbacksConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\CallbacksDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\CallbacksEdgeDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\KeyConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\KeyDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\KeyEdgeDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\MoneyDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\PageInfoDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\PriceUpdateQuotaDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\ProductConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\ProductDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\ProductEdgeDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\SalesConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\SalesDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\SalesEdgeDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\StockConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\StockDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\StockEdgeDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\TransactionsConnectionDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\TransactionsDenormalizer;
use Fypex\EnebaClient\Denormalizer\Object\TransactionsEdgeDenormalizer;
use Fypex\EnebaClient\Model\Relay\Edge\TransactionsEdge;
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
