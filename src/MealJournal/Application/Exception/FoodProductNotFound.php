<?php

declare(strict_types=1);

namespace Kcalculator\MealJournal\Application\Exception;

use RuntimeException;

final class FoodProductNotFound extends RuntimeException
{
    public static function withId(int $productId): self
    {
        return new self(sprintf('Food product with id %d was not found.', $productId));
    }
}
