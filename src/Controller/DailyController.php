<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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
  public function uploadFood()
  {
    $host= 'localhost';
    $user= 'root';
    $pass= '';
    $dbname= 'foods';
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

      echo "Wartości na 100g produktu<br>";
      echo "$row[0], $row[1] kcal  <br>";
     
      echo "Białko: $row[2]g<br>";
      echo "Tłuszcz: $row[3]g<br>";
      echo "Węglowodany: $row[4]g";
      
      return $this->render('User/afterlogin.html.twig', []);

    } else {
      echo"Wpisz nazwę produktu";
      return $this->render('User/afterlogin.html.twig', []);
    } 



  }


}
