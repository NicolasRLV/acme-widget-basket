<?php

declare(strict_types=1);

namespace GrowSnap\AcmeBasket;

class BuyOneGetSecondHalfPriceOffer implements OfferInterface
{
    private string $productCode;

    public function __construct(string $productCode)
    {
        $this->productCode = $productCode;
    }

    public function apply(array $items, array $productCatalog): string
    {
        $count = array_count_values($items)[$this->productCode] ?? 0;
        if ($count < 2) {
            return '0.00';
        }

        $pairs = (int)($count / 2);
        $price = (string)$productCatalog[$this->productCode];
        $discountPerPair = bcdiv($price, '2', 2); // Changed to scale 2
        return bcmul((string)$pairs, $discountPerPair, 2); // Changed to scale 2
    }
}
