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

class DailyController extends AbstractController
{
/**
   * @Route("/daily", name="daily")
   */
  public function szukaj()
  {
    return $this->render('User/afterlogin.html.twig', []);
    
  }


/**
   * @Route("/uploadFood", name="uploadFood")
   */
  public function findFood()
  {
    $host= 'localhost';
    $user= 'root';
    $pass= '';
    $dbname= 'kcalculator';
    if(isset($_POST["search"]) && $_POST["search"] != "")
    {
      $conn = mysqli_connect($host, $user, $pass, $dbname);
      if(!$conn)
      {
        echo"No connection";
        return;
      }

      $product = $_POST["search"];
      $question = "SELECT Produkt, Energia, Białko, Tłuszcz, Węglowodany FROM tabkal WHERE Produkt = '$product'";
      $result = mysqli_query($conn, $question);
      $row = mysqli_fetch_row($result);
 
      return $this->render('User/afterlogin.html.twig', []);

    } else {
      return $this->render('User/afterlogin.html.twig', []);
    } 

  }


}

