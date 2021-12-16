<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Form;

use App\Entity\UsersEntries;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductDetailsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Meals', ChoiceType::class, [
                'choices' => [
                    'Przekąska' => 'Przekąska',
                    'Śniadanie' => 'Śniadanie',
                    'Drugie śniadanie' => 'Drugie śniadanie',
                    'Obiad' => 'Obiad',
                    'Podwieczorek' => 'Podwieczorek',
                    'Kolacja' => 'Kolacja'
                ],
                'label' => 'Wybierz posiłek'
            ])
            ->add('Grammage', ChoiceType::class, [
                'choices' => [
                    '100 g' => '1',
                    '12.5 g' => '0.125',
                    '25 g' => '0.25',
                    '33 g' => '0.333',
                    '37.5 g' => '0.375',
                    '50 g' => '0.50',
                    '62.5 g' => '0.625',
                    '66 g' => '0.666',
                    '75 g' => '0.75',
                    '87.5 g' => '0.875',
                    '200 g' => '2',
                    '300 g' => '3',
                    '400 g' => '4',
                    '500 g' => '5'
                ],
                'label' => 'Wielkość porcji'
            ])
            // ->add('changingValues', EntityType::class, [
            //     'class' => UsersEntries::class,
            //     'placeholder' => '',
            // ])
        ;

        // $builder->addEventListener(
        //     FormEvents::PRE_SET_DATA,
        //     function (FormEvent $event) {
        //         $form = $event->getForm();

        //         // this would be your entity, i.e. SportMeetup
        //         $data = $event->getData();

        //         $sport = $data->getSport();
        //         $positions = null === $sport ? [] : $sport->getAvailablePositions();

        //         $form->add('position', EntityType::class, [
        //             'class' => Position::class,
        //             'placeholder' => '',
        //             'choices' => $positions,
        //         ]);
        //     }
        // );
                
    }
}