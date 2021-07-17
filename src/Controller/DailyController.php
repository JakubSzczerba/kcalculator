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
   * @Route("/wpisy/delete/{id}", name="deleteEntry")
   */
  public function deleteEntry(Request $request, int $id, EntityManagerInterface $entityManager)
  { 
    
    $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find($id);

    if ($id)
    {
      
      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($entry);
      $entityManager->flush();
      return $this->redirectToRoute('showEntries');
    } 
    else {
      return $this->render('User/testEntry.html.twig', []);
    }


  }

  /**
   * @Route("/wpisy/edit/{id}",  methods="GET|POST", name="editEntry")
   */
  public function editEntry(Request $request, int $id, Products $food, EntityManagerInterface $entityManager)
  {
    $entry = new UsersEntries(); 
    $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find(array('id' => $id,));

    $product = $entityManager->getRepository(UsersEntries::class)->find($food);

    $meal_type = '';

    if(!empty($_POST['Meals'])) 
    {
      $meal_type = $_POST['Meals'];
    }   
    
    $entry->setMealType($meal_type);
    
    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

    if( flush()) 
    {
      return $this->redirectToRoute('showEntries');
    }   
      
    else {
    return $this->render('User/editEntry.html.twig', [
      'entry' => $entry,
      'product' => $product,

     
    ]); 
  }
  }

  /**
   * @Route("/wpisy",  methods="GET|POST", name="showEntries")
   */
  public function showEntries(Request $request, EntriesRepository $entriesRepository): Response
  {
    $id = $this->getUser()->getId();
    $datetime;
    $meal='';
    $meal1 = "Przekąska";
    $meal2 = "Śniadanie";
    $meal3 = "Drugie śniadanie";
    $meal4 = "Obiad";
    $meal5 = "Podwieczorek";
    $meal6 = "Kolacja";

    if (isset($_POST['dataTocheckDaily']))
    { 
      //echo "jest";
      $datetime = new \DateTime($_POST['dataTocheckDaily']);
    }
    else {
      //echo "nie jest";
      $datetime = new \DateTime('@'.strtotime('now'));
    }

    $showEntry = $entriesRepository->displayEntry($datetime, $id); //all entries per day, but products are not grouping in one row (meal)

    $ShowSnack = $entriesRepository->ShowSnack($datetime, $id, $meal1); // try to fetch all entries per day for row -> Przękąski 

    $ShowBreakfast = $entriesRepository->ShowBreakfast($datetime, $id, $meal2); // the same way ^ but with Śnaidanie!

    $ShowLunch = $entriesRepository->ShowLunch($datetime, $id, $meal3); // DRUGIE ŚNAIDANIE  dinner

    $ShowDinner = $entriesRepository->ShowDinner($datetime, $id, $meal4); // OBIAD

    $ShowTea = $entriesRepository->ShowTea($datetime, $id, $meal5); // Podwieczorek




    return $this->render('User/testEntry.html.twig', [
      'entry' => $showEntry,
      'snack' => $ShowSnack,
      'breakfast' => $ShowBreakfast,
      'lunch' => $ShowLunch,
      'dinner' => $ShowDinner,
      'tea' => $ShowTea,
    ]);
  
  }





}
