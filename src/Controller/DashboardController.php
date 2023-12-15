<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Controller;

use Kcalculator\Prodiver\Chart\Dashboard\MacronutrientsProvider;
use Kcalculator\Prodiver\Chart\Dashboard\WeightProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Kcalculator\Repository\DashboardCaloriesRepository;
use Kcalculator\Repository\EntriesRepository;
use Kcalculator\Repository\UserWeightHistoryRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
    private DashboardCaloriesRepository $dashboardCaloriesRepository;

    private EntriesRepository $entriesRepository;

    private UserWeightHistoryRepository $userWeightHistoryRepository;

    private ChartBuilderInterface $chartBuilder;

    private MacronutrientsProvider $macronutrientsProvider;

    private WeightProvider $weightProvider;

    public function __construct(DashboardCaloriesRepository $dashboardCaloriesRepository, EntriesRepository $entriesRepository, UserWeightHistoryRepository $userWeightHistoryRepository, ChartBuilderInterface $chartBuilder, MacronutrientsProvider $macronutrientsProvider, WeightProvider $weightProvider)
    {
        $this->dashboardCaloriesRepository = $dashboardCaloriesRepository;
        $this->entriesRepository = $entriesRepository;
        $this->userWeightHistoryRepository = $userWeightHistoryRepository;
        $this->chartBuilder = $chartBuilder;
        $this->macronutrientsProvider = $macronutrientsProvider;
        $this->weightProvider = $weightProvider;
    }

    #[Route('/dashboard', name: 'dashboard')]
    public function dashboard(): Response
    {
        $id = $this->getUser()->getId();
        $datetime = new \DateTime('@' . strtotime('now'));

        $preferention = $this->dashboardCaloriesRepository->showKcalPerDay($id);

        $summKcal = $this->entriesRepository->SummEntriedKcal($datetime, $id);
        $summProtein = $this->entriesRepository->SummEntriedProteins($datetime, $id);
        $summFat = $this->entriesRepository->SummEntriedFats($datetime, $id);
        $summCarbo = $this->entriesRepository->SummEntriedCarbo($datetime, $id);

        // charts queries
        $showHistory = $this->userWeightHistoryRepository->showHistory($id);
        $monthHistory = $this->userWeightHistoryRepository->monthHistory($id);

        // Chart for MACRO implementation:
        $chartMacro = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chartMacro->setData($this->macronutrientsProvider->getData($summProtein, $summFat, $summCarbo));

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
                'summKcal' => $summKcal,
                'summProtein' => $summProtein,
                'summFat' => $summFat,
                'summCarbo' => $summCarbo,
                'chartMacro' => $chartMacro,
                'chartWeight' => $chartWeight,
            ]
        );
    }
}
