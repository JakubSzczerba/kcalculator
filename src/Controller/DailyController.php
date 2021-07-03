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
   * @Route("/product", methods="POST", name="findFood")
   */
  public function findFood(Request $request, ProductRepository $products): Response
  {
    $nameproduct = $_POST["search"];
    $foundProducts = $products->findProducts($nameproduct);
    //$oneResult = $_GET["showThisProduct"];


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
      

  }

  

  /**
   * @Route("/product/{id}", name="product.detail")
   */
  public function showOneProduct(int $id, Products $product): Response
  {
  
    return $this->render('User/loadEntry.html.twig', [
      'product' => $product,
    ]);
    
    

  }

  /**
   * @Route("/product{id}", methods="POST", name="addEntry")
   */
  public function addEntry(int $id, Request $request, EntityManagerInterface $entityManager, Products $product): Response
  { 
    
    $em = $this->getDoctrine()->getManager();

    $meal_type = '';
    $grammage = '';
    //Grammage

    if(!empty($_POST['Meals'])) 
    {
      $meal_type = $_POST['Meals'];
    } 

    if(!empty($_POST['Grammage']))
    {
      $grammage = $_POST['Grammage'];
    }
    
    
    $product = $em->getRepository(Products::class)->find($id);

    $entry = new UsersEntries();

    $entry->setUser($this->getUser());
    $entry->setDateTime(new \DateTime());
    $entry->setMealType($meal_type);
    $entry->setGrammage($grammage);
    $entry->setFood($product);


    $entityManager->persist($entry);
    $entityManager->flush();


    return $this->render('User/daily.html.twig', [
      'product' => $product,
    ]);

  }

  /**
   * @Route("/daily", methods="POST", name="showEntries")
   */
  public function showEntries(Request $request, EntriesRepository $entriesRepository): Response
  {
    return $this->render('User/daily.html.twig', []);
  
  }





}

