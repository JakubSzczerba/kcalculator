<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Entry\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\Domain\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users_entries')]
class UserEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\ManyToOne(targetEntity: "Kcalculator\Domain\User\Entity\User", inversedBy: "entry")]
    #[ORM\JoinColumn(name: "user_id", referencedColumnName: 'id', nullable: false)]
    private User $user;

    #[ORM\Column]
    private \DateTime $datetime;

    #[ORM\Column(type: "string", nullable: false)]
    private string $meal_type;

    #[ORM\Column(type: "float", nullable: false)]
    private float $grammage;

    #[ORM\Column(type: "float", nullable: false)]
    private float $energyXgram;

    #[ORM\Column(type: "float", nullable: false)]
    private float $proteinXgram;

    #[ORM\Column(type: "float", nullable: false)]
    private float $fatXgram;

    #[ORM\Column(type: "float", nullable: false)]
    private float $carboXgram;

    #[ORM\ManyToMany(targetEntity: "Kcalculator\Domain\Product\Entity\Product", inversedBy: "entries")]
    #[ORM\JoinTable(name: "entry_product")]
    private Collection $food;

    public function __construct()
    {
        $this->datetime = new \DateTime();
        $this->food = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateTime(): \DateTime
    {
        return $this->datetime;
    }

    public function setDateTime(\DateTime $datetime): void
    {
        $this->datetime = $datetime;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getMealType(): ?string
    {
        return $this->meal_type;
    }

    public function setMealType($meal_type): void
    {
        $this->meal_type = $meal_type;
    }

    public function getGrammage(): ?float
    {
        return $this->grammage;
    }

    public function setGrammage($grammage): void
    {
        $this->grammage = $grammage;
    }

    public function getEnergyXgram(): ?float
    {
        return $this->energyXgram;
    }

    public function setEnergyXgram($energyXgram): void
    {
        $this->energyXgram = $energyXgram;
    }

    public function getProteinXgram(): ?float
    {
        return $this->proteinXgram;
    }

    public function setProteinXgram($proteinXgram): void
    {
        $this->proteinXgram = $proteinXgram;
    }

    public function getFatXgram(): ?float
    {
        return $this->fatXgram;
    }

    public function setFatXgram($fatXgram): void
    {
        $this->fatXgram = $fatXgram;
    }

    public function getCarboXgram(): ?float
    {
        return $this->carboXgram;
    }

    public function setCarboXgram($carboXgram): void
    {
        $this->carboXgram = $carboXgram;
    }

    public function getFood(): ?Collection
    {
        return $this->food;
    }

    public function setFood(?Product $food): self
    {
        $this->food[] = $food;
        return $this;
    }
}