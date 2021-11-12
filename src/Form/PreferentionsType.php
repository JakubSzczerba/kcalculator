<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PreferentionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Mężczyzna' => 'man',
                    'Kobieta' => 'woman'
                ],
                'label' => 'Płeć'
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Waga'
            ])
            ->add('height', TextType::class,  [
                'label' => 'Wzrost'
            ])
            ->add('age', TextType::class,  [
                'label' => 'Wiek'
            ])
            ->add('activity', ChoiceType::class, [
                'choices' => [
                    'Niska aktywność w ciągu dnia' => 'activity1',
                    'Średnia aktywność w ciągu dnia' => 'activity2',
                    'Wysoka aktywność w ciągu dnia' => 'activity3'
                ],
                'label' => 'Aktywność'
            ])
            ->add('intentions', ChoiceType::class, [
                'choices' => [
                    'Utrata wagi' => 'intension1',
                    'Utrzymanie masy ciała' => 'intension2',
                    'Budowa masy mięśniowej' => 'intension3'
                ],
                'label' => 'Intencje'
            ])
        ;
    }
}