<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Factory\Entry;

use Kcalculator\Entity\Products;
use Kcalculator\Entity\User;
use Kcalculator\Entity\UsersEntries;
use Doctrine\ORM\EntityManagerInterface;

class EntryFactory
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(User $user, string $mealType, float $grammage, Products $product, float $energy, float $protein, float $fat, float $carbohydrates): UsersEntries
    {
        $entry = new UsersEntries();
        $entry->setUser($user);
        $entry->setDateTime(new \DateTime());
        $entry->setMealType($mealType);
        $entry->setGrammage($grammage);
        $entry->setFood($product);
        $entry->setEnergyXgram($energy);
        $entry->setProteinXgram($protein);
        $entry->setFatXgram($fat);
        $entry->setCarboXgram($carbohydrates);

        $this->em->persist($entry);
        $this->em->flush();

        return $entry;
    }

    public function edit(UsersEntries $entry, string $mealType, float $grammage, float $energy, float $protein, float $fat, float $carbohydrates): UsersEntries
    {
        $entry->setMealType($mealType);
        $entry->setGrammage($grammage);
        $entry->setEnergyXgram($energy);
        $entry->setProteinXgram($protein);
        $entry->setFatXgram($fat);
        $entry->setCarboXgram($carbohydrates);

        $this->em->flush();

        return $entry;
    }
}