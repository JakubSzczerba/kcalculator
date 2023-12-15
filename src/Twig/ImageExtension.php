<?php

namespace Kcalculator\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;


class ImageExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('findFood', [$this, 'findFood']),
            new TwigFunction('kcal', [$this, 'kcal']),
        ];
    }



    //public function findFood()
    //{
    /*
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

      echo "** Wartości na 100g produktu **<br>";
      echo "<br>";
      echo "<h4><bold>$row[0], $row[1] kcal</bold></h4>  <br>";
     
      echo "Białko: $row[2]g<br>";
      echo "Tłuszcz: $row[3]g<br>";
      echo "Węglowodany: $row[4]g";
      
      

    } else {
      echo"Wpisz nazwę produktu";
      
    } 
    */
    //}
}
