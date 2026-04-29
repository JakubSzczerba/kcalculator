<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Controller;

use Kcalculator\Application\Command\Preferention\EditPreferenceCommand;
use Kcalculator\Application\Command\Preferention\SetPreferenceCommand;
use Kcalculator\Application\Form\PreferenceType;
use Kcalculator\Application\Services\Preference\FormDataExtractor;
use Kcalculator\Domain\Preference\Entity\Preference;
use Kcalculator\Domain\User\Entity\User;
use Kcalculator\Infrastructure\Repository\PreferenceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

class PreferenceController extends AbstractController
{
    private PreferenceRepository $preferenceRepository;

    private FormDataExtractor $formDataExtractor;

    private MessageBusInterface $commandBus;

    public function __construct(PreferenceRepository $preferenceRepository, FormDataExtractor $formDataExtractor, MessageBusInterface $commandBus)
    {
        $this->preferenceRepository = $preferenceRepository;
        $this->formDataExtractor = $formDataExtractor;
        $this->commandBus = $commandBus;
    }

    #[Route('/preferention', name: 'preferention', methods: ['GET', 'POST'])]
    public function setPreferention(Request $request): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Authenticated user is required to manage preferences.');
        }

        $existingPreference = $this->preferenceRepository->findOneBy(['user' => $user]);

        if ($existingPreference instanceof Preference) {
            return $this->redirectToRoute('editPreferentions', ['id' => $existingPreference->getId()]);
        }

        $form = $this->createForm(PreferenceType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $preferentionDTO = $this->formDataExtractor->extractPreferentionDTO($form);

            $command = new SetPreferenceCommand($user, $preferentionDTO);
            $this->commandBus->dispatch($command);
            $this->addFlash('success', 'Obliczono dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->renderPreferenceForm($form->createView(), false);
    }

    #[Route('/preferention/{id}/edit', name: 'editPreferentions', methods: ['GET', 'POST'])]
    public function editPreferentions(Request $request, int $id): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Authenticated user is required to manage preferences.');
        }

        $preferention = $this->preferenceRepository->findOneBy([
            'id' => $id,
            'user' => $user,
        ]);

        if (!$preferention instanceof Preference) {
            throw $this->createNotFoundException('Preference profile was not found.');
        }

        $form = $this->createForm(PreferenceType::class, $preferention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $preferentionDTO = $this->formDataExtractor->extractPreferentionDTO($form);

            $command = new EditPreferenceCommand($preferention, $preferentionDTO);
            $this->commandBus->dispatch($command);
            $this->addFlash('success', 'Edytowano dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->renderPreferenceForm($form->createView(), true, $preferention);
    }

    private function renderPreferenceForm(mixed $formView, bool $isEditMode, ?Preference $preference = null): Response
    {
        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $formView,
            'isEditMode' => $isEditMode,
            'preference' => $preference,
        ]);
    }
}
