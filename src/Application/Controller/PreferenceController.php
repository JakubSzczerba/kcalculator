<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Kcalculator\Application\Command\Preferention\EditPreferenceCommand;
use Kcalculator\Application\Command\Preferention\SetPreferenceCommand;
use Kcalculator\Application\Form\PreferenceType;
use Kcalculator\Application\Services\Preference\FormDataExtractor;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Domain\Preference\Entity\Preference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class PreferenceController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private FormDataExtractor $formDataExtractor;

    private MessageBusInterface $commandBus;

    public function __construct(EntityManagerInterface $entityManager, FormDataExtractor $formDataExtractor, MessageBusInterface $commandBus)
    {
        $this->entityManager = $entityManager;
        $this->formDataExtractor = $formDataExtractor;
        $this->commandBus = $commandBus;
    }

    #[Route('/preferention', name: 'preferention', methods: ['POST'])]
    public function setPreferention(Request $request): Response
    {
        $user = $this->entityManager->getRepository(User::class)->find($this->getUser()->getId());
        $form = $this->createForm(PreferenceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $preferentionDTO = $this->formDataExtractor->extractPreferentionDTO($form);

            $command = new SetPreferenceCommand($user, $preferentionDTO);
            $this->commandBus->dispatch($command);
            $this->addFlash('success', 'Obliczono dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/preferention/{id}/edit', name: 'editPreferentions', methods: ['GET|POST'])]
    public function editPreferentions(Request $request, int $id): Response
    {
        $preferention = $this->entityManager->getRepository(Preference::class)->find(['id' => $id]);
        $form = $this->createForm(PreferenceType::class, $preferention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $preferentionDTO = $this->formDataExtractor->extractPreferentionDTO($form);

            $command = new EditPreferenceCommand($preferention, $preferentionDTO);
            $this->commandBus->dispatch($command);
            $this->addFlash('success', 'Edytowano dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}