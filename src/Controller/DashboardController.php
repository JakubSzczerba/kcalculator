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

class DashboardController extends AbstractController
{
/**
   * @Route("/dashboard", name="dashboard")
   */
  public function dashboard(Request $request, DashboardCaloriesRepository $caloriesrep, EntriesRepository $entried_kcalRepository): Response
  {
    $id = $this->getUser()->getId();
    $datetime = new \DateTime('@'.strtotime('now'));
    
    
    $preferention = $caloriesrep->showKcalPerDay($id);

    $summKcal = $entried_kcalRepository->SummEntriedKcal($datetime, $id); 

    //ceate var for summ:
    
      $summProtein = $entried_kcalRepository->SummEntriedProteins($datetime, $id);
      $summFat;      // SummEntriedFats
      $summCarbo;    // SummEntriedCarbo



    return $this->render('Homepage/homeafterlog.html.twig', [
      'preferentions' => $preferention,
      'summKcal' => $summKcal,
      'summProtein' => $summProtein,
    ]);

    $results = [];
    
    foreach ($preferention as $pref) {
      $results[]=[
        'kcalday' => $pref->getKcalDay,
      ];

    }
    return $results; 


  }



  




}
