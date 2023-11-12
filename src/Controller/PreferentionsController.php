<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use App\Command\Preferention\EditPreferentionCommand;
use App\Command\Preferention\SetPreferentionCommand;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Entity\User;
use App\Form\PreferentionsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserPreferention;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreferentionsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/preferention', name: 'preferention', methods: ['POST'])]
    public function setPreferention(Request $request, MessageBusInterface $commandBus): Response
    {
        $form = $this->createForm(PreferentionsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new SetPreferentionCommand(
                $this->entityManager->getRepository(User::class)->find($this->getUser()->getId()),
                $form->get('gender')->getData(),
                $form->get('weight')->getData(),
                (float)$form->get('height')->getData(),
                (int)$form->get('age')->getData(),
                $form->get('activity')->getData(),
                $form->get('intentions')->getData()
            );
            $commandBus->dispatch($command);

            $this->addFlash('success', 'Obliczono dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/preferention/{id}/edit', name: 'editPreferentions', methods: ['GET|POST'])]
    public function editPreferentions(int $id, Request $request, MessageBusInterface $commandBus): Response
    {
        $preferention = $this->getDoctrine()->getRepository(UserPreferention::class)->find(array('id' => $id,));
        $form = $this->createForm(PreferentionsType::class, $preferention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new EditPreferentionCommand(
                $preferention,
                $this->entityManager->getRepository(User::class)->find($this->getUser()->getId()),
                $form->get('gender')->getData(),
                $form->get('weight')->getData(),
                (float)$form->get('height')->getData(),
                (int)$form->get('age')->getData(),
                $form->get('activity')->getData(),
                $form->get('intentions')->getData()
            );
            $commandBus->dispatch($command);

            $this->addFlash('success', 'Edytowano dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
} 
