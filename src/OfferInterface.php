<?php

declare(strict_types=1);

namespace GrowSnap\AcmeBasket;

// Topic 1: Interface Definition
interface OfferInterface
{
    // Topic 2: Apply Method
    public function apply(array $items, array $productCatalog): string; // Returns discount as a string for bcmath
}
