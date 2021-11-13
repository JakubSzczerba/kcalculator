<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\DashboardCaloriesRepository; 
use App\Repository\EntriesRepository;
use App\Repository\UserWeightHistoryRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
  private DashboardCaloriesRepository $dashboardCaloriesRepository;

  private EntriesRepository $entriesRepository;

  private UserWeightHistoryRepository $userWeightHistoryRepository;

  private ChartBuilderInterface $chartBuilder;

  public function __construct(
    DashboardCaloriesRepository $dashboardCaloriesRepository,
    EntriesRepository $entriesRepository,
    UserWeightHistoryRepository $userWeightHistoryRepository,
    ChartBuilderInterface $chartBuilder
  ) {
    $this->dashboardCaloriesRepository = $dashboardCaloriesRepository;
    $this->entriesRepository = $entriesRepository;
    $this->userWeightHistoryRepository = $userWeightHistoryRepository;  
    $this->chartBuilder = $chartBuilder;
  }

/**
   * @Route("/dashboard", name="dashboard")
   */
  public function dashboard()
  {
    $id = $this->getUser()->getId();
    $datetime = new \DateTime('@'.strtotime('now'));
     
    $preferention = $this->dashboardCaloriesRepository->showKcalPerDay($id);

    $summKcal = $this->entriesRepository->SummEntriedKcal($datetime, $id); 
    $summProtein = $this->entriesRepository->SummEntriedProteins($datetime, $id);
    $summFat = $this->entriesRepository->SummEntriedFats($datetime, $id);
    $summCarbo = $this->entriesRepository->SummEntriedCarbo($datetime, $id);

    // charts queries
    $showHistory = $this->userWeightHistoryRepository->showHistory($id);
    $monthHistory = $this->userWeightHistoryRepository->monthHistory($id);

    //get weight from user's history and fetch in a single array
    $results = [];
    foreach ($showHistory as $weight ) {  
      foreach($weight as $value) {

        array_push($results, $value);
          }
    }

    //get datatime from user's history, format all datatime for only name of month and fetch in a single array
    $months = [];
    foreach ($monthHistory as $month ) {   
      foreach($month as $value){
      
        $x = $value->format('d.m');      
        array_push($months, $x);
          }
    }

    // Chart for MACRO implementation:
    $chartMacro = $this->chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
        $chartMacro->setData([
          'labels'=> [
            'Białko',
            'Tłuszcz',
            'Węglowodany'
          ],
            'datasets' => [
                [
                    'label' => 'My First dataset',
                    'backgroundColor' => [
                      '#0099FF',
                      '#FF6633',
                      '#663300'],
                    'data' => [$summProtein, $summFat, $summCarbo],
                ],
            ],
        ]);

    // Chart for Weight implementations:
      $chartWeight = $this->chartBuilder->createChart(Chart::TYPE_LINE);
      $chartWeight->setData([
          'labels' => $months,
          'datasets' => [
              [
                  'label' => 'Zmiana wagi',
                  'backgroundColor' => '',
                  'borderColor' => 'green',
                  'data' => $results
              ],
          ],
      ]);
     
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
