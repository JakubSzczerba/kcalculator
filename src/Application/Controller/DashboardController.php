<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Controller;

use Kcalculator\Application\Prodiver\Chart\Dashboard\MacronutrientsProvider;
use Kcalculator\Application\Prodiver\Chart\Dashboard\WeightProvider;
use Kcalculator\Infrastructure\Repository\PreferenceRepository;
use Kcalculator\Infrastructure\Repository\WeightHistoryRepository;
use Kcalculator\MealJournal\Application\Port\DailyNutritionSummaryReader;
use Kcalculator\Domain\User\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
    public function __construct(
        private readonly PreferenceRepository $dashboardCaloriesRepository,
        private readonly DailyNutritionSummaryReader $dailyNutritionSummaryReader,
        private readonly WeightHistoryRepository $userWeightHistoryRepository,
        private readonly ChartBuilderInterface $chartBuilder,
        private readonly MacronutrientsProvider $macronutrientsProvider,
        private readonly WeightProvider $weightProvider,
    ) {
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();

        if (!$user instanceof User) {
            throw $this->createAccessDeniedException('Authenticated user is required to open the dashboard.');
        }

        $id = $user->getId();
        $datetime = new \DateTime('@' . strtotime('now'));

        $preferention = $this->dashboardCaloriesRepository->showKcalPerDay($id);
        $nutritionSummary = $this->dailyNutritionSummaryReader->getForDay($datetime, $id);

        // charts queries
        $showHistory = $this->userWeightHistoryRepository->showHistory($id);
        $monthHistory = $this->userWeightHistoryRepository->monthHistory($id);

        // Chart for MACRO implementation:
        $chartMacro = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chartMacro->setData($this->macronutrientsProvider->getData(
            $nutritionSummary->getProtein(),
            $nutritionSummary->getFat(),
            $nutritionSummary->getCarbohydrates(),
        ));

        //get weight from user's history and fetch in a single array
        $results = [];
        foreach ($showHistory as $weight) {
            foreach ($weight as $value) {
                $results[] = $value;
            }
        }
        //get datetime from user's history, format all datetime for only name of month and fetch in a single array
        $months = [];
        foreach ($monthHistory as $month) {
            foreach ($month as $value) {
                $x = $value->format('d.m');
                $months[] = $x;
            }
        }

        // Chart for Weight implementations:
        $chartWeight = $this->chartBuilder->createChart(Chart::TYPE_LINE);
        $chartWeight->setData($this->weightProvider->getData($months, $results));

        return $this->render('User/Dashboard/index.html.twig', [
                'preferentions' => $preferention,
                'summKcal' => $nutritionSummary->getEnergy(),
                'summProtein' => $nutritionSummary->getProtein(),
                'summFat' => $nutritionSummary->getFat(),
                'summCarbo' => $nutritionSummary->getCarbohydrates(),
                'chartMacro' => $chartMacro,
                'chartWeight' => $chartWeight,
            ]
        );
    }
}
