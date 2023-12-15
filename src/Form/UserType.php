<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fullName', TextType::class, [
                'label' => 'Imie i nazwisko',
                'attr' => array(
                    'placeholder' => 'Johny Bravo'
                )
            ])
            ->add('username', TextType::class, [
                'label' => 'Nazwa użytkownika',
                'attr' => array(
                    'placeholder' => 'john87'
                )
            ])
            ->add('email', EmailType::class, [
                'label' => 'Adres e-mail',
                'attr' => array(
                    'placeholder' => 'jbravo@test.com'
                )
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => ['label' => 'Hasło'],
                'second_options' => ['label' => 'Potwierdź hasło']
            ]);
    }
}
