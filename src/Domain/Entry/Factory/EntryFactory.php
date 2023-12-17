<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Domain\Entry\Factory;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Domain\Product\Entity\Product;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\Entry\Entity\UserEntry;

class EntryFactory implements EntryFactoryInterface
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function new(User $user, string $mealType, float $grammage, Product $product, float $energy, float $protein, float $fat, float $carbohydrates): UserEntry
    {
        $entry = new UserEntry();
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

    public function edit(UserEntry $entry, string $mealType, float $grammage, float $energy, float $protein, float $fat, float $carbohydrates): UserEntry
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