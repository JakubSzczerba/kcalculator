<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Factory\Preference\PreferenceFactory;
use App\Factory\Preference\UserWeightHistoryFactory;
use App\Form\PreferentionsType;
use App\Services\Preference\BasalMetabolicRateAlgorithm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\UserPreferention;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PreferentionsController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    private BasalMetabolicRateAlgorithm $basalMetabolicRateAlgorithm;

    private PreferenceFactory $preferenceFactory;

    private UserWeightHistoryFactory $userWeightHistoryFactory;

    public function __construct(EntityManagerInterface $entityManager, BasalMetabolicRateAlgorithm $basalMetabolicRateAlgorithm, PreferenceFactory $preferenceFactory, UserWeightHistoryFactory $userWeightHistoryFactory)
    {
        $this->entityManager = $entityManager;
        $this->basalMetabolicRateAlgorithm = $basalMetabolicRateAlgorithm;
        $this->preferenceFactory = $preferenceFactory;
        $this->userWeightHistoryFactory = $userWeightHistoryFactory;
    }

    #[Route('/preferention', name: 'preferention', methods: ['POST'])]
    public function setPreferention(Request $request): Response
    {
        $form = $this->createForm(PreferentionsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->entityManager->getRepository(User::class)->find($this->getUser()->getId());
            $gender = $form->get('gender')->getData();
            $weight = $form->get('weight')->getData();
            $height = (float) $form->get('height')->getData();
            $age = (int) $form->get('age')->getData();
            $activity = $form->get('activity')->getData();
            $intentions = $form->get('intentions')->getData();

            /* Calculate Basal Metabolic Rate per user */
            $BMR = $this->basalMetabolicRateAlgorithm->calculate($gender, $weight, $height, $age, $activity, $intentions);

            /* Persist data */
            $this->preferenceFactory->new($user, $gender, $weight, $height, $age, $activity, $BMR['caloric_requirement'], $intentions, $BMR['kcal_per_day'], $BMR['protein'], $BMR['fat'], $BMR['carbohydrates']);
            $this->userWeightHistoryFactory->new($user, $weight);

            $this->addFlash('success', 'Obliczono dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/preferention/{id}/edit', name: 'editPreferentions', methods: ['GET|POST'])]
    public function editPreferentions(int $id, Request $request)
    {
        $preferention = $this->getDoctrine()->getRepository(UserPreferention::class)->find(array('id' => $id,));
        $form = $this->createForm(PreferentionsType::class, $preferention);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->entityManager->getRepository(User::class)->find($this->getUser()->getId());
            $gender = $form->get('gender')->getData();
            $weight = $form->get('weight')->getData();
            $height = (float) $form->get('height')->getData();
            $age = (int) $form->get('age')->getData();
            $activity = $form->get('activity')->getData();
            $intentions = $form->get('intentions')->getData();

            $BMR = $this->basalMetabolicRateAlgorithm->calculate($gender, $weight, $height, $age, $activity, $intentions);

            $this->preferenceFactory->edit($preferention, $gender, $weight, $height, $age, $activity, $BMR['caloric_requirement'], $intentions, $BMR['kcal_per_day'], $BMR['protein'], $BMR['fat'], $BMR['carbohydrates']);
            $this->userWeightHistoryFactory->new($user, $weight);

            $this->addFlash('success', 'Edytowano dziennie zapotrzebowanie kaloryczne');

            return $this->redirectToRoute('dashboard');
        }

        return $this->render('User/Preferentions/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
} 
