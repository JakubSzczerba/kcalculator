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
                ]
            ])
            ->add('weight', TextType::class)
            ->add('height', TextType::class)
            ->add('age', TextType::class)
            ->add('activity', ChoiceType::class, [
                'choices' => [
                    'activity1' => 'niską aktywność w ciągu dnia',
                    'activity2' => 'średnią aktywność w ciągu dnia',
                    'activity3' => 'wysoką aktywność w ciągu dnia'
                ]
            ])
            ->add('intentions', ChoiceType::class, [
                'choices' => [
                    'intension1' => 'zredukować tkankę tłuszczową',
                    'intension2' => 'utrzymać masę ciała',
                    'intension3' => 'zbudować masę mięśniową'
                ]
            ])
        ;
    }
}