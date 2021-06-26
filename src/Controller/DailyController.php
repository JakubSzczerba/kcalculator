<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Repository\ProductRepository;
use App\Entity\User;
use App\Entity\UsersEntries;
use App\Entity\Products;

class DailyController extends AbstractController
{
/**
   * @Route("/daily", name="daily")
   */
  public function daily()
  {
    
    return $this->render('User/daily.html.twig', []);
    
  }


/**
   * @Route("/findFood", methods="POST", name="findFood")
   */
  public function findFood(Request $request, ProductRepository $products): Response
  {
    $nameproduct = $_POST["search"];
    $foundProducts = $products->findProducts($nameproduct);


    return $this->render('User/searchproducts.html.twig',[
      'products' => $foundProducts
    ]);

    $results = [];
    
    foreach ($foundProducts as $product) {
      $results[]=[
        'product' => $product->getProduct,
        'energy' => $product->getEnergy,
        'protein' => $product->getProtein,
        'fat' => $product->getFat,
        'carbo' => $product->getCarbo,

      ];

    }
    return $results; 
    
    //$addEntry->addEntry($foundProducts);
    
    

  }

  

  /**
   * @Route("/showOneProduct", methods="POST", name="showOneProduct")
   */
  public function showOneProduct(Request $request, ProductRepository $products): Response
  {
    /* $em = $this->getDoctrine()->getManager();

    $meal_type ="jakis posilek";
    $grammage = 1;

    $product = $em->getRepository(Products::class)->find(2);

    $entry = new UsersEntries();

    $entry->setUser($this->getUser());
    $entry->setDateTime(new \DateTime());
    $entry->setMealType($meal_type);
    $entry->setGrammage($grammage);
    $entry->setFood($product);

    $entityManager->persist($entry);
    $entityManager->flush();
    

      // $entry->setFood($this->getProducts());

    */
    

    //cos tam       loadEntry.html.twig
    return $this->render('User/loadEntry.html.twig', [
      'products' => $foundProducts
    ]);
  }

  
  // addEntry -> photo in tell.
  // commented content is fof addEntry


}

