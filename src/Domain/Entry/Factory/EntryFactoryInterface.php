<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Entry\Factory;

use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\Entry\Entity\Entry;

interface EntryFactoryInterface
{
    public function new(User $user, string $mealType, float $grammage, Product $product, float $energy, float $protein, float $fat, float $carbohydrates): Entry;

    public function edit(Entry $entry, string $mealType, float $grammage, float $energy, float $protein, float $fat, float $carbohydrates): Entry;
}