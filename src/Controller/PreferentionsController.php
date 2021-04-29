<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
   * @Route("/kcal", name="kcal")
   */
  public function kcal()
  { 
    $result = 0;
    $sex;
    $weight = $_POST['weight']; 
    $height = $_POST['height']; 
    $age = $_POST['age'];
    //$low = $_POST['activity1']; // 1.45;
    //$medium = $_POST['activity2']; // 1.75;
    //$hard = $_POST['activity3']; // 2.0;

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
    echo $result;



    return $this->render('User/preferentions.html.twig', []);
  }

}

