<?php

declare(strict_types=1);

namespace GrowSnap\AcmeBasket\Tests;

require_once __DIR__ . '/../vendor/autoload.php';

use GrowSnap\AcmeBasket\Basket;
use GrowSnap\AcmeBasket\BuyOneGetSecondHalfPriceOffer;
use PHPUnit\Framework\TestCase;

class BasketTest extends TestCase
{
    private Basket $basket;

    private array $productCatalog = [
        'R01' => 32.95,
        'G01' => 24.95,
        'B01' => 7.95,
    ];

    private array $deliveryRules = [
        'under_50' => 4.95,
        'under_90' => 2.95,
        'over_90' => 0.0,
    ];

    protected function setUp(): void
    {
        $offers = [
            new BuyOneGetSecondHalfPriceOffer('R01'),
        ];
        $this->basket = new Basket($this->productCatalog, $this->deliveryRules, $offers);
    }

    public function testAddProduct(): void
    {
        $this->basket->add('R01');
        $this->basket->add('G01');
        $this->assertCount(2, $this->basket->getItems());
    }

    public function testTotalCalculation1(): void
    {
        $this->basket->add('B01');
        $this->basket->add('G01');
        $this->assertEquals(37.85, $this->basket->total(), '', 0.02);
    }

    public function testTotalCalculation2(): void
    {
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(54.38, $this->basket->total(), '', 0.02);
    }

    public function testTotalCalculation3(): void
    {
        $this->basket->add('R01');
        $this->basket->add('G01');
        $this->assertEquals(60.85, $this->basket->total(), '', 0.02);
    }

    public function testTotalCalculation4(): void
    {
        $this->basket->add('B01');
        $this->basket->add('B01');
        $this->basket->add('R01');
        $this->basket->add('R01');
        $this->assertEquals(68.28, $this->basket->total(), '', 0.02);
    }

    public function testInvalidProductCodeThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid product code: X01');
        $this->basket->add('X01');
        $this->basket->total();
    }
}
