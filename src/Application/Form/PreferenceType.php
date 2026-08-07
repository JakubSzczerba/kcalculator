<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PreferenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('gender', ChoiceType::class, [
                'choices' => [
                    'Mężczyzna' => 'man',
                    'Kobieta' => 'woman'
                ],
                'label' => 'Płeć',
                'placeholder' => 'Wybierz płeć',
                'help' => 'Ta wartość wpływa na wyliczenie podstawowej przemiany materii.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Wybierz płeć.']),
                ],
            ])
            ->add('weight', NumberType::class, [
                'label' => 'Waga (kg)',
                'attr' => [
                    'placeholder' => '68.5',
                    'step' => '0.1',
                    'min' => '25',
                    'max' => '400',
                ],
                'help' => 'Aktualna masa ciała posłuży do wyliczenia celu oraz zapisze pierwszy wpis w historii wagi.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Podaj aktualną wagę.']),
                    new Assert\Positive(['message' => 'Waga musi być większa od zera.']),
                    new Assert\Range([
                        'min' => 25,
                        'max' => 400,
                        'notInRangeMessage' => 'Waga musi mieścić się w zakresie od {{ min }} do {{ max }} kg.',
                    ]),
                ],
            ])
            ->add('height', NumberType::class, [
                'label' => 'Wzrost (cm)',
                'attr' => [
                    'placeholder' => '173',
                    'step' => '0.1',
                    'min' => '100',
                    'max' => '250',
                ],
                'help' => 'Wzrost w centymetrach. W razie potrzeby możesz użyć jednego miejsca po przecinku.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Podaj wzrost.']),
                    new Assert\Positive(['message' => 'Wzrost musi być większy od zera.']),
                    new Assert\Range([
                        'min' => 100,
                        'max' => 250,
                        'notInRangeMessage' => 'Wzrost musi mieścić się w zakresie od {{ min }} do {{ max }} cm.',
                    ]),
                ]
            ])
            ->add('age', IntegerType::class, [
                'label' => 'Wiek',
                'attr' => [
                    'placeholder' => '31',
                    'min' => '13',
                    'max' => '120',
                ],
                'help' => 'Wiek wpływa na wyliczenie zapotrzebowania bazowego.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Podaj wiek.']),
                    new Assert\Positive(['message' => 'Wiek musi być większy od zera.']),
                    new Assert\Range([
                        'min' => 13,
                        'max' => 120,
                        'notInRangeMessage' => 'Wiek musi mieścić się w zakresie od {{ min }} do {{ max }} lat.',
                    ]),
                ],
            ])
            ->add('activity', ChoiceType::class, [
                'choices' => [
                    'Siedzący tryb życia i mało ruchu' => 'activity1',
                    'Umiarkowana aktywność w pracy lub po godzinach' => 'activity2',
                    'Wysoka aktywność i regularny ciężki trening' => 'activity3'
                ],
                'label' => 'Aktywność',
                'placeholder' => 'Wybierz poziom aktywności',
                'help' => 'Wybierz poziom, który najlepiej opisuje większość Twoich dni, a nie pojedynczy trening.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Wybierz poziom aktywności.']),
                ],
            ])
            ->add('intentions', ChoiceType::class, [
                'choices' => [
                    'Chcę spalić tkankę tłuszczową' => 'intension1',
                    'Chcę utrzymać wagę' => 'intension2',
                    'Chcę przybrać masy mięśniowej' => 'intension3'
                ],
                'label' => 'Cel',
                'placeholder' => 'Wybierz główny cel',
                'help' => 'Cel wpływa na końcowy limit kalorii i rozkład makroskładników.',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Wybierz główny cel.']),
                ],
            ]);
    }
}
