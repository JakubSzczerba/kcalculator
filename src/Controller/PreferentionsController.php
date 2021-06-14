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

    
    if ( isset($_POST['man']) || isset($_POST['woman']) )
    {
      if (isset($_POST['man']))
      {
        $gender = 'man';
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif (isset($_POST['woman']))
      {
        $gender = 'woman';
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }
    
    }

    if ( isset($_POST['activity1']) || isset($_POST['activity2']) || isset($_POST['activity3']) )
    {
      if (isset($_POST['activity1']))
      {
        $activity = 'low';
        $result = $result * 1.45;
      }
      elseif (isset($_POST['activity2']))
      {
        $activity = 'medium';
        $result = $result * 1.75;
      }
      elseif (isset($_POST['activity3']))
      {
        $activity = 'high';
        $result = $result * 2.0;
      }  
      
    } 

    settype($result, "integer");

    $caloric_requirement = $caloric_requirement + $result;

    
    if ( isset($_POST['intension1']) || isset($_POST['intension2']) || isset($_POST['intension3']) )
    {

      if (isset($_POST['intension1']))
      {
        $intentions = 'burn';
        $kcal_day = ($caloric_requirement + $burn);
      }
      elseif (isset($_POST['intension2']))
      {
        $intentions = 'keep';
        $kcal_day = ($caloric_requirement + $keep);
      }
      elseif (isset($_POST['intension3']))
      {
        $intentions = 'gain';
        $kcal_day = ($caloric_requirement + $gain);
      }  
      
    } 
    
    settype($kcal_day, "integer");

    $users = new User();
    $users->getId();
    

    $preferention = new UserPreferention();
    $preferention->setGender($gender);
    $preferention->setWeight($weight);
    $preferention->setHeight($height);
    $preferention->setAge($age);
    $preferention->setActivity($activity);
    $preferention->setKcal($caloric_requirement);
    $preferention->setIntentions($intentions);
    $preferention->setKcalDay($kcal_day);

    $preferention->setUsers($users);

    $entityManager->persist($users);
    $entityManager->persist($preferention);
    $entityManager->flush();

    return $this->render('User/loadedpreferentions.html.twig', [
      'preferentions' => $preferention
    ]);





    /*
    $dataToGet = [];
    foreach ($preferention as $preferentions) {     
      $dataToGet[] = [
        'gender' => $preferention->getGender(),
        'weight' => $preferention->getWeight(),
        'height' => $preferention->getHeight(),
        'age' => $preferention->getAge(),
        'activity' => $preferention->getActivity(),
        'caloric_requirement' => $preferention->getKcal(),
        'intentions' => $preferention->getIntentions(),
        'kcal_day' => $preferention->getKcalDay()
      ];    
    };
    return $dataToGet;
    */
    
    
    

       
  }






} 