<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Prodiver\Entry;

use App\Entity\Products;

class EntryDataProvider
{
    public function getGrammageValues(float $grammage, Products $product): array
    {
        $energy = $product->getEnergy() * $grammage;
        $protein = $product->getProtein() * $grammage;
        $fat = $product->getFat() * $grammage;
        $carbohydrates = $product->getCarbo() * $grammage;

        $array['energy'] = round($energy, 0);
        $array['protein'] = round($protein, 2);
        $array['fat'] = round($fat, 2);
        $array['carbohydrates'] = round($carbohydrates, 2);
        $array['grammage'] = $grammage;

        return $array;
    }
}