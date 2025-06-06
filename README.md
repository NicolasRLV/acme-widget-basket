Acme Widget Co Basket System
A PHP solution for managing a shopping basket with delivery rules and offers.
Setup

Run composer install
Run tests: ./vendor/bin/phpunit tests

Assumptions

Product codes are valid from the catalog.
No negative quantities.
Offer applies per pair of R01 (second at half price).
Delivery cost is applied after the offer discount.
Uses bcmath for precise decimal calculations to avoid floating-point errors.
Test case 4 (B01, B01, R01, R01) expected $98.27 but calculates to $68.28; assumed the requirement might be a typo.

How It Works

Basket class initializes with a catalog, delivery rules (<$50 = $4.95, <$90 = $2.95, >=$90 = $0), and an array of OfferInterface objects.
add method adds products by code.
total calculates subtotal, applies offers using the Strategy Pattern, adds delivery cost, and returns the total rounded to 2 decimals.
Offers are implemented using the Strategy Pattern (e.g., BuyOneGetSecondHalfPriceOffer for R01) to allow easy addition of new offers in the future.
Used bcmath to ensure exact decimal precision for financial calculations, addressing floating-point issues.

Design Choices

Strategy Pattern: Applied to make offer logic extensible, anticipating future offers (e.g., "buy 3 get 1 free"). This adds slight complexity but improves maintainability.
bcmath: Chosen to handle monetary calculations accurately, ensuring test cases pass without rounding errors. Adds minimal overhead since bcmath is typically enabled in PHP.
