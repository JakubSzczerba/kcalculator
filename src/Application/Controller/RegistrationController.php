<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Controller;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Application\Form\UserType;
use Kcalculator\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'registration')]
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager, Session $session): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User($form->get('fullName')->getData(), $form->get('username')->getData(), $form->get('email')->getData());
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );
            $user->setPassword($hashedPassword);
            $user->setRoles($user->getRoles());
            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $session->getFlashBag()->add('success', sprintf('Account %s has been created!', $user->getUsername()));
                return $this->redirectToRoute('home');
            } catch (UniqueConstraintViolationException $exception) {
                $session->getFlashBag()->add('danger', 'Email and username has to be unique');
            }
        }

        return $this->render('User/Account/Register/index.html.twig', [
                'form' => $form->createView()
            ]
        );
    }
}