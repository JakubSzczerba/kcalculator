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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
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
   * @Route("/wpisy/edit/{entryId}/{productId} ",  methods="GET|POST", name="editEntry")
   * @ParamConverter("entryId", options={"id" = "entryId"})
   * @ParamConverter("productId", options={"id" = "productId"})
   */
  public function editEntry(Request $request, UsersEntries $entryId, Products $productId, EntityManagerInterface $entityManager)
  {
    $entry = new UsersEntries(); 
    $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find(array('id' => $entryId,));

    $product = $entityManager->getRepository(UsersEntries::class)->find($productId);

    /*$meal_type = '';

    if(!empty($_POST['Meals'])) 
    {
      $meal_type = $_POST['Meals'];
    }   
    
    $entry->setMealType($meal_type); */
    
    
    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->flush();

      
    
    return $this->render('User/editEntry.html.twig', [
      'entry' => $entry,
      'product' => $product,

    ]); 

  
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

    $ShowSupper = $entriesRepository->ShowSupper($datetime, $id, $meal6); // Koalcja 

    //summ kcal for meals near to name of meal
    $SummSnacksKcal = $entriesRepository->SummSnacksKcal($datetime, $id, $meal1); // {{ snackcal|number_format }} 
    $SummBreakfast = $entriesRepository->SummBreakfast($datetime, $id, $meal2);  //  {{ breakcal|number_format }} 
    $SummLunch = $entriesRepository->SummLunch($datetime, $id, $meal3);         // {{ lunchkcal|number_format }} 
    $SummDinner = $entriesRepository->SummDinner($datetime, $id, $meal4);      // {{ dinnerkcal|number_format }}
    $SummTea = $entriesRepository->SummTea($datetime, $id, $meal5);           // {{ teakcal|number_format }}
    $SummSupper = $entriesRepository->SummSupper($datetime, $id, $meal6);    // {{ supperkcal|number_format }}




    return $this->render('User/testEntry.html.twig', [
      'entry' => $showEntry,
      'snack' => $ShowSnack,
      'breakfast' => $ShowBreakfast,
      'lunch' => $ShowLunch,
      'dinner' => $ShowDinner,
      'tea' => $ShowTea,
      'supper' => $ShowSupper,
      'snackcal' => $SummSnacksKcal, 
      'breakcal' => $SummBreakfast,
      'lunchkcal' => $SummLunch,
      'dinnerkcal' => $SummDinner,
      'teakcal' => $SummTea,
      'supperkcal' => $SummSupper,
    ]);
  
  }





}
