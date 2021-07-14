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
use App\Repository\EntriesRepository;
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
    $energyXgram;
    $proteinXgram;
    $fatXgram;
    $carboXgram;
   
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
    
    $energy = $product->getEnergy();
    $protein = $product->getProtein();
    $fat = $product->getFat();
    $carbo = $product->getCarbo();
    
    $energyXgram = $energy * $grammage;
    $proteinXgram = $protein * $grammage;
    $fatXgram = $fat * $grammage;
    $carboXgram = $carbo * $grammage;

    $energyXgram = round($energyXgram, 0);
    $proteinXgram = round($proteinXgram, 2);
    $fatXgram = round($fatXgram, 2);
    $carboXgram = round($carboXgram, 2);

    
    $entry = new UsersEntries();

    $entry->setUser($this->getUser());
    $entry->setDateTime(new \DateTime());
    $entry->setMealType($meal_type);
    $entry->setGrammage($grammage);
    $entry->setFood($product);
    $entry->setEnergyXgram($energyXgram);
    $entry->setProteinXgram($proteinXgram);
    $entry->setFatXgram($fatXgram);
    $entry->setCarboXgram($carboXgram);

    $entityManager->persist($entry);
    $entityManager->flush();



    if ($this->getUser()) 
        {
        return $this->redirectToRoute('showEntries');
             
        } else 
        {
        return $this->render('User/daily.html.twig', [
          'product' => $product,
        ]);
        }

  }

  /**
   * @Route("/product/delete/{id}")
   * @Method({"DELETE"})
   */
  public function deleteEntry()
  {

  }

  /**
   * @Route("/wpisy",  methods="GET", name="showEntries")
   */
  public function showEntries(Request $request, EntriesRepository $entriesRepository): Response
  {
    $id = $this->getUser()->getId();
    $datetime = new \DateTime('@'.strtotime('now'));

    $showEntry = $entriesRepository->displayEntry($datetime, $id);




    return $this->render('User/testEntry.html.twig', [
      'entry' => $showEntry
    ]);
  
  }





}
