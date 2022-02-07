<?php
declare(strict_types=1);

namespace Fypex\EnebaClient\Model;

use DateTimeInterface;
use Money\Money;
use Ramsey\Uuid\UuidInterface;

class Stock
{
    private $id;
    private $product;
    private $unitsSold;
    private $onHand;
    private $declaredStock;
    private $status;
    private $expiresAt;
    private $autoRenew;
    private $price;
    private $createdAt;

    /**
     * @var PriceUpdateQuota|null
     */
    private $priceUpdateQuota;

    public function __construct(
        UuidInterface $id,
        Product $product,
        int $unitsSold,
        int $onHand,
        int $declaredStock,
        string $status,
        DateTimeInterface $expiresAt,
        bool $autoRenew,
        Money $price,
        DateTimeInterface $createdAt
    ) {
        $this->id = $id;
        $this->product = $product;
        $this->unitsSold = $unitsSold;
        $this->onHand = $onHand;
        $this->declaredStock = $declaredStock;
        $this->status = $status;
        $this->expiresAt = $expiresAt;
        $this->autoRenew = $autoRenew;
        $this->price = $price;
        $this->createdAt = $createdAt;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function getUnitsSold(): int
    {
        return $this->unitsSold;
    }

    public function getOnHand(): int
    {
        return $this->onHand;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getExpiresAt(): DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function isAutoRenew(): bool
    {
        return $this->autoRenew;
    }

    public function getPrice(): Money
    {
        return $this->price;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getPriceUpdateQuota(): ?PriceUpdateQuota
    {
        return $this->priceUpdateQuota;
    }

    public function getDeclaredStock(): int
    {
        return $this->declaredStock;
    }

    public function setPriceUpdateQuota(?PriceUpdateQuota $priceUpdateQuota): void
    {
        $this->priceUpdateQuota = $priceUpdateQuota;
    }
}
