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
    $gender;

    $weight = $_POST['weight']; 
    $height = $_POST['height']; 
    $age = $_POST['age'];

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
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif (isset($_POST['woman']))
      {
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }
    
    }

    if ( isset($_POST['activity1']) || isset($_POST['activity2']) || isset($_POST['activity3']) )
    {
      if (isset($_POST['activity1']))
      {
        $result = $result * 1.45;
      }
      elseif (isset($_POST['activity2']))
      {
        $result = $result * 1.75;
      }
      elseif (isset($_POST['activity3']))
      {
        $result = $result * 2.0;
      }  
    } 

    settype($result, "integer");
    //return $result;
    echo $result;

    $result = $caloric_requirement;
     
    
    
    if ( isset($_POST['intension1']) || isset($_POST['intension2']) || isset($_POST['intension3']) )
    {
      $kcal_day = $caloric_requirement;
      settype($kcal_day, "integer");

      if (isset($_POST['intension1']))
      {
        $kcal_day = ($kcal_day + $burn);
      }
      elseif (isset($_POST['intension2']))
      {
        $kcal_day = ($kcal_day + $keep);
      }
      elseif (isset($_POST['intension3']))
      {
        $kcal_day = ($kcal_day + $gain);
      }  
      
    } 
    
    //settype($kcal_day, "integer");
    //return $kcal_day;
    echo $kcal_day;
    
    return $this->render('User/preferentions.html.twig', []);
    
    /*
    return $this->render('User/loadedpreferentions.html.twig',[
      'preferentions' => $set_preferention
    ]);
    */


       
  }






} 