<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\UserPreferention;
use App\Entity\User;

class PreferentionsController extends AbstractController
{
/**
   * @Route("/preferentions", name="preferentions")
   */
  public function preferentions()
  {
    return $this->render('User/preferentions.html.twig', []);
  }




/**
   * @Route("/setPreferention", methods="POST", name="setPreferention")
   */
  public function setPreferention(Request $request, EntityManagerInterface $entityManager): Response
  {

    $result = 0;
    $caloric_requirement = 0;   // zapotrzebowanie ogólne
    $kcal_day = 0;              // zapotrzebowanie wedłóg preferencji
  
    $gender = '';
    $weight = $_POST['weight']; 
    $height = $_POST['height']; 
    $age = $_POST['age'];
    $activity = '';
    $intentions = '';



    $low = isset($_POST['activity1']); // 1.45;
    $medium = isset($_POST['activity2']); // 1.75;
    $hard = isset($_POST['activity3']); // 2.0;

    $burn = -300;           //-300;
    $keep = 0;              //0;
    $gain = 300;            //+300;

    $protein = 1;
    $fat = 1;
    $carbo = 1;
    
    if ( isset($_POST['man']) || isset($_POST['woman']) )
    {
      if (isset($_POST['man']))
      {
        $gender = 'Mężczyzna';
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif (isset($_POST['woman']))
      {
        $gender = 'Kobieta';
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }
    
    }

    if ( isset($_POST['activity1']) || isset($_POST['activity2']) || isset($_POST['activity3']) )
    {
      if (isset($_POST['activity1']))
      {
        $activity = 'niską aktywność w ciągu dnia';
        $result = $result * 1.45;
        
      }
      elseif (isset($_POST['activity2']))
      {
        $activity = 'średnią aktywność w ciągu dnia';
        $result = $result * 1.75;
        
      }
      elseif (isset($_POST['activity3']))
      {
        $activity = 'wysoką aktywność w ciągu dnia';
        $result = $result * 2.0;
        
      }  
      
    } 

    settype($result, "integer");

    $caloric_requirement = $caloric_requirement + $result;

    
    if ( isset($_POST['intension1']) || isset($_POST['intension2']) || isset($_POST['intension3']) )
    {

      if (isset($_POST['intension1']))
      {
        $intentions = 'zredukować tkankę tłuszczową';
        $kcal_day = ($caloric_requirement + $burn);
        $protein = 2 * $weight; // ilosc bialka dla osob chcacych SCHUDNAC
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif (isset($_POST['intension2']))
      {
        $intentions = 'utrzymać masę ciała';
        $kcal_day = ($caloric_requirement + $keep);
        $protein = 1.60 * $weight; // ilosc bialka dla osob chcacych UTRZYMAC WAGE
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif (isset($_POST['intension3']))
      {
        $intentions = 'zbudować masę mięśniową';
        $kcal_day = ($caloric_requirement + $gain);
        $protein = 1.85 * $weight; // ilosc bialka dla osob chcacych PRZYTYĆ
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }  
      
    } 
    
    settype($kcal_day, "integer");
    settype($protein, "integer");
    settype($fat, "integer");
    settype($carbo, "integer");

    
    $preferention = new UserPreferention();
    $preferention->setGender($gender);
    $preferention->setWeight($weight);
    $preferention->setHeight($height);
    $preferention->setAge($age);
    $preferention->setActivity($activity);
    $preferention->setKcal($caloric_requirement);
    $preferention->setIntentions($intentions);
    $preferention->setKcalDay($kcal_day);
    $preferention->setProteinPerDay($protein);
    $preferention->setFatPerDay($fat);
    $preferention->setCarboPerDay($carbo);

    $preferention->setUsers($this->getUser());

    
    $entityManager->persist($preferention);
    $entityManager->flush();

    return $this->render('User/loadedpreferentions.html.twig', [
      'preferentions' => $preferention
    ]);
       
  }


  /**
   * @Route("/editPreferention/{id}", methods="GET|POST", name="editPreferention")
   */
  public function editPreferention(Request $request, int $id, EntityManagerInterface $entityManager)
  {

    $preferention = new UserPreferention();
    $preferention = $this->getDoctrine()->getRepository(UserPreferention::class)->find(array('id' => $id,));


    //pomysle pozniej o nowym szablonie itd


    // szablon tylko dla sprawdzenia 
    return $this->render('User/daily.html.twig', [
      'preferention' => $preferention,
 
    ]); 

  }






} 