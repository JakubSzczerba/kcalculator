<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Products;

class EntryDTO
{
    private float $grammage;

    private string $meal;

    private  Products $product;

    public function __construct(string $grammage, string $meal, Products $product)
    {
        $this->grammage = (float)$grammage;
        $this->meal = $meal;
        $this->product = $product;
    }

    public function getGrammage(): float
    {
        return $this->grammage;
    }

    public function getMeal(): string
    {
        return $this->meal;
    }


    public function getProduct(): Products
    {
        return $this->product;
    }
}