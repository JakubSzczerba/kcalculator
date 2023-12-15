<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity()
 * @ORM\Table(name="products")
 */
class Products
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="Kcalculator\Entity\UsersEntries", mappedBy="food")"
     *
     */
    private Collection $entries;

    /**
     * @ORM\Column(type="string")
     */
    private string $product;

    /**
     * @ORM\Column(type="float")
     */
    private float $energy;

    /**
     * @ORM\Column(type="float")
     */
    private float $protein;

    /**
     * @ORM\Column(type="float")
     */
    private float $fat;

    /**
     * @ORM\Column(type="float")
     */
    private float $carbo;

    public function __construct()
    {
        $this->entries = new \Doctrine\Common\Collections\ArrayCollection();
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