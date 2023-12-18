<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Preference\Entity;

use Kcalculator\Domain\User\Entity\User;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'user_preferention')]
class UserPreference
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $id;

    #[ORM\OneToOne(inversedBy: "preferentions", targetEntity: "Kcalculator\Domain\User\Entity\User")]
    #[ORM\JoinColumn(name: "users_id", referencedColumnName: 'id', nullable: false)]
    private User $users;

    #[ORM\Column(type: "string", nullable: false)]
    private string $gender;

    #[ORM\Column(type: "float", nullable: false)]
    private float $weight;

    #[ORM\Column(type: "float", nullable: false)]
    private float $height;

    #[ORM\Column(type: "integer", nullable: false)]
    private int $age;

    #[ORM\Column(type: "string", nullable: false)]
    private string $activity;

    #[ORM\Column(type: "integer", nullable: false)]
    public int $caloric_requirement;

    #[ORM\Column(type: "string", nullable: false)]
    private string $intentions;

    #[ORM\Column(type: "integer", nullable: false)]
    public int $kcal_day;

    #[ORM\Column(type: "integer", nullable: false)]
    public int $proteinPerDay;

    #[ORM\Column(type: "integer", nullable: false)]
    public int $fatPerDay;

    #[ORM\Column(type: "integer", nullable: false)]
    public int $carboPerDay;

    public function getId()
    {
        return $this->id;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;
        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender($gender): void
    {
        $this->gender = $gender;
    }

    public function getIntentions(): ?string
    {
        return $this->intentions;
    }

    public function setIntentions($intentions): void
    {
        $this->intentions = $intentions;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight($weight): void
    {
        $this->weight = $weight;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(float $height): void
    {
        $this->height = $height;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): void
    {
        $this->age = $age;
    }

    public function getActivity(): ?string
    {
        return $this->activity;
    }

    public function setActivity($activity): void
    {
        $this->activity = $activity;
    }

    public function getKcal(): ?int
    {
        return $this->caloric_requirement;
    }

    public function setKcal($caloric_requirement): void
    {
        $this->caloric_requirement = $caloric_requirement;
    }

    public function getKcalDay(): ?int
    {
        return $this->kcal_day;
    }

    public function setKcalDay($kcal_day): void
    {
        $this->kcal_day = $kcal_day;
    }

    public function getProteinPerDay(): ?int
    {
        return $this->proteinPerDay;
    }

    public function setProteinPerDay($proteinPerDay): void
    {
        $this->proteinPerDay = $proteinPerDay;
    }

    public function getFatPerDay(): ?int
    {
        return $this->fatPerDay;
    }

    public function setFatPerDay($fatPerDay): void
    {
        $this->fatPerDay = $fatPerDay;
    }

    public function getCarboPerDay(): ?int
    {
        return $this->carboPerDay;
    }

    public function setCarboPerDay($carboPerDay): void
    {
        $this->carboPerDay = $carboPerDay;
    }
}