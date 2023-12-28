<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Product\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Product
{
    private $id;

    private Collection $entries;

    private string $product;

    private float $energy;

    private float $protein;

    private float $fat;

    private float $carbo;

    public function __construct()
    {
        $this->entries = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct($product): void
    {
        $this->product = $product;
    }

    public function getEnergy(): ?float
    {
        return $this->energy;
    }

    public function setEnergy($energy): void
    {
        $this->energy = $energy;
    }

    public function getProtein(): ?float
    {
        return $this->protein;
    }

    public function setProtein($protein): void
    {
        $this->protein = $protein;
    }

    public function getFat(): ?float
    {
        return $this->fat;
    }

    public function setFat($fat): void
    {
        $this->fat = $fat;
    }

    public function getCarbo(): ?float
    {
        return $this->carbo;
    }

    public function setCarbo($carbo): void
    {
        $this->carbo = $carbo;
    }

    public function getEntry(): ArrayCollection
    {
        return $this->entries;
    }
}