<?php

declare(strict_types=1);

namespace GrowSnap\AcmeBasket;

class Basket
{
    // Topic 1: Class Properties
    private array $productCatalog;
    private array $deliveryRules;
    private array $offers = [];
    private array $items = [];

    // Topic 2: Constructor
    public function __construct(array $productCatalog, array $deliveryRules, array $offers = [])
    {
        $this->productCatalog = $productCatalog;
        $this->deliveryRules = $deliveryRules;
        $this->offers = $offers;
    }

    // Topic 3: Item Management
    public function add(string $productCode): void
    {
        $this->items[] = $productCode;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    // Topic 4: Total Calculation
    public function total(): float
    {
        // Step 1: Calculate base subtotal
        $subtotal = '0.00';

        foreach ($this->items as $productCode) {
            if (!isset($this->productCatalog[$productCode])) {
                throw new \InvalidArgumentException("Invalid product code: $productCode");
            }

            $subtotal = bcadd($subtotal, (string)$this->productCatalog[$productCode], 2);
        }

        // Step 2: Apply offers
        foreach ($this->offers as $offer) {
            $discount = $offer->apply($this->items, $this->productCatalog);
            $subtotal = bcsub($subtotal, $discount, 2);
        }

        // Step 3: Apply delivery cost based on the subtotal after discount
        $deliveryCost = '0.00';
        if ((float)$subtotal < 50) {
            $deliveryCost = (string)$this->deliveryRules['under_50'];
        } elseif ((float)$subtotal < 90) {
            $deliveryCost = (string)$this->deliveryRules['under_90'];
        } else {
            $deliveryCost = (string)$this->deliveryRules['over_90'];
        }

        // Step 4: Calculate total with bcmath
        $total = bcadd($subtotal, $deliveryCost, 2);
        return (float)$total;
    }
}
