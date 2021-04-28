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
    $man;
    $woman;
    $weight; 
    $height; 
    $age;
    $low = $_POST['activity1']; // 1.45;
    $medium = $_POST['activity2']; // 1.75;
    $hard = $_POST['activity3']; // 2.0;

    if (isset($man))
    {
      $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
    }

    elseif (isset($woman))
    {
      $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
    }
    
    if (isset($low))
    {
      $result = $result * 1.45;
    }

    elseif (isset($medium))
    {
      $result = $result * 1.75;
    }

    elseif (isset($hard))
    {
      $result = $result * 2.0;
    }

    echo $result;



    return $this->render('User/preferentions.html.twig', []);
  }

}

