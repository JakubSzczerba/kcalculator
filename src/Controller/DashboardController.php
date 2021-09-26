<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Repository\DashboardCaloriesRepository; 
use App\Repository\EntriesRepository;
use App\Repository\UserWeightHistoryRepository;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class DashboardController extends AbstractController
{
/**
   * @Route("/dashboard", name="dashboard")
   */
  public function dashboard(
                            Request $request, DashboardCaloriesRepository $caloriesrep, EntriesRepository $entried_kcalRepository, 
                            UserWeightHistoryRepository $userWeight, ChartBuilderInterface $chartBuilder
                            ): Response
  {
    $id = $this->getUser()->getId();
    $datetime = new \DateTime('@'.strtotime('now'));
    
    
    $preferention = $caloriesrep->showKcalPerDay($id);

    $summKcal = $entried_kcalRepository->SummEntriedKcal($datetime, $id); 
  
    $summProtein = $entried_kcalRepository->SummEntriedProteins($datetime, $id);
    $summFat = $entried_kcalRepository->SummEntriedFats($datetime, $id);
    $summCarbo = $entried_kcalRepository->SummEntriedCarbo($datetime, $id);

    // charts queries
    $showHistory = $userWeight->showHistory($id);
    $monthHistory = $userWeight->monthHistory($id);

    //get weight from user's history and fetch in a single array
    $results = [];
    foreach ($showHistory as $weight ) {   
      foreach($weight as $value) {

        array_push($results, $value);

          }
    }

    //get data from user's history, farmat all datatime for only name of month and fetch in a single array
    $months = [];
    foreach ($monthHistory as $month ) {   
      foreach($month as $value){
      
        $x = $value->format('d.m');      
        array_push($months, $x);

          }
    }

    // Chart for MACRO implementation:
    $chartMacro = $chartBuilder->createChart(Chart::TYPE_DOUGHNUT);
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
      $chartWeight = $chartBuilder->createChart(Chart::TYPE_LINE);
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
     
//'fill' => false,



    return $this->render('Homepage/homeafterlog.html.twig', [
      'preferentions' => $preferention,
      'summKcal' => $summKcal,
      'summProtein' => $summProtein,
      'summFat' => $summFat,
      'summCarbo' => $summCarbo,
      'chartMacro' => $chartMacro,
      'chartWeight' => $chartWeight,
    ]);

    



  }
}