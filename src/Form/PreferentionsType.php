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
                    'Siedzący tryb życia, praca przy biurku' => 'activity1',
                    'Tryb życia o średniej aktywności fizczynej. Praca fizyczna, lub codziennie wykonywanie lekkich ćwiczeń fizycznych w czasie 1 godziny' => 'activity2',
                    'Tryb życia o wysokiej aktywności fizczynej. Ciężka praca fizyczna, albo codzienny trening o wymiarze minimum 2 godzin z wysoką intesywnością' => 'activity3'
                ],
                'label' => 'Aktywność'
            ])
            ->add('intentions', ChoiceType::class, [
                'choices' => [
                    'Chcę spalić tkankę tłuszczową' => 'intension1',
                    'Chcę utrzymać wagę' => 'intension2',
                    'Chcę przybrać masy mięśniowej' => 'intension3'
                ],
                'label' => 'Intencje'
            ])
        ;
    }
}