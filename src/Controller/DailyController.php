<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\ProductRepository;
use App\Repository\EntriesRepository;
use App\Entity\UsersEntries;
use App\Entity\Products;
use Symfony\Component\HttpFoundation\Request;
use App\Form\ProductDetailsType;

class DailyController extends AbstractController
{
  private ProductRepository $productRepository;

  private EntriesRepository $entriesRepository;

  private EntityManagerInterface $entityManager;

  public function __construct(
    ProductRepository $productRepository,
    EntriesRepository $entriesRepository,
    EntityManagerInterface $entityManager
  ) {
    $this->productRepository = $productRepository;
    $this->entriesRepository = $entriesRepository;
    $this->entityManager = $entityManager;  
  }

/**
   * @Route("/product", methods="POST", name="findFood")
   */
  public function findFood(Request $request): Response
  {
    $nameproduct = $request->get('search');
    $foundProducts = $this->productRepository->findProducts($nameproduct);
  
    return $this->render('User/Daily/Products/searchedProducts.html.twig',[
      'products' => $foundProducts,
      'nameproduct' => $nameproduct,
    ]);
  }

  /**
   * @Route("/product/{id}", methods="GET|POST", name="addEntry")
   */
  public function addEntry(Request $request, Products $product, int $id)
  {   
    $em = $this->getDoctrine()->getManager();
    $form = $this->createForm(ProductDetailsType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid())
    {
      $meal_type = $form->get('Meals')->getData();
      $grammage = $form->get('Grammage')->getData();
      
      // getting infroamtion about selected product
      $product = $em->getRepository(Products::class)->find($id);
      $energy = $product->getEnergy();
      $protein = $product->getProtein();
      $fat = $product->getFat();
      $carbo = $product->getCarbo();

      // recalculation of product's informations by selected grammage
      $energyXgram = $energy * $grammage;
      $proteinXgram = $protein * $grammage;
      $fatXgram = $fat * $grammage;
      $carboXgram = $carbo * $grammage;

      // rounding results
      $energyXgram = round($energyXgram, 0);
      $proteinXgram = round($proteinXgram, 2);
      $fatXgram = round($fatXgram, 2);
      $carboXgram = round($carboXgram, 2);

      // Creating Entry
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

      $this->entityManager->persist($entry);
      $this->entityManager->flush();

      return $this->redirectToRoute('showEntries'); 
    }

    return $this->render('User/Daily/Products/productDetails.html.twig', [
      'product' => $product,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/wpisy/delete/{id}", name="deleteEntry")
   */
  public function deleteEntry(int $id)
  {     
    $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find($id);

    if ($id)
    {    
      $this->entityManager = $this->getDoctrine()->getManager();
      $this->entityManager->remove($entry);
      $this->entityManager->flush();

      return $this->redirectToRoute('showEntries');
    } 
    else {
      return $this->render('User/Daily/index.html.twig', []);
    }
  }
  
 /**
   * @Route("/wpisy/edit/{id}",  methods="GET|POST", name="editEntry")
   */
  public function editEntry(Request $request, int $id)
  {
    $entry = $this->getDoctrine()->getRepository(UsersEntries::class)->find(array('id' => $id,));

    $form = $this->createForm(ProductDetailsType::class);
    $form->handleRequest($request);
    $choosenProduct = $entry->getFood();
    
    $product = [];

    foreach ($choosenProduct as $productDetails ) {  
      $product = $productDetails;
    }

    if ($form->isSubmitted() && $form->isValid())
    {
      $meal_type = $form->get('Meals')->getData();
      $grammage = $form->get('Grammage')->getData();

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

      $entry->setMealType($meal_type);
      $entry->setGrammage($grammage);
      $entry->setEnergyXgram($energyXgram);
      $entry->setProteinXgram($proteinXgram);
      $entry->setFatXgram($fatXgram);
      $entry->setCarboXgram($carboXgram);

      $this->entityManager->flush();

      return $this->redirectToRoute('showEntries');
    } 

    return $this->render('User/Daily/Products/productDetails.html.twig', [
      'product' => $product,
      'form' => $form->createView(),
    ]);
  }

  /**
   * @Route("/wpisy",  methods="GET|POST", name="showEntries")
   */
  public function showEntries(Request $request): Response
  {
    $id = $this->getUser()->getId();
    $meal1 = "Przekąska";
    $meal2 = "Śniadanie";
    $meal3 = "Drugie śniadanie";
    $meal4 = "Obiad";
    $meal5 = "Podwieczorek";
    $meal6 = "Kolacja";

    if ($request->get('dataTocheckDaily'))
    { 
      $datetime = new \DateTime($request->get('dataTocheckDaily'));
    }

    else {
      $datetime = new \DateTime('@'.strtotime('now'));
    }

    $showEntry = $this->entriesRepository->displayEntry($datetime, $id); //all entries per day, but products are not grouping in one row (meal)
    $ShowSnack = $this->entriesRepository->ShowSnack($datetime, $id, $meal1); // try to fetch all entries per day for row -> Przękąski 
    $ShowBreakfast = $this->entriesRepository->ShowBreakfast($datetime, $id, $meal2); // the same way ^ but with Śnaidanie!
    $ShowLunch = $this->entriesRepository->ShowLunch($datetime, $id, $meal3); // DRUGIE ŚNAIDANIE  dinner
    $ShowDinner = $this->entriesRepository->ShowDinner($datetime, $id, $meal4); // OBIAD
    $ShowTea = $this->entriesRepository->ShowTea($datetime, $id, $meal5); // Podwieczorek  
    $ShowSupper = $this->entriesRepository->ShowSupper($datetime, $id, $meal6); // Koalcja 

    //summ kcal for meals near to name of meal
    $SummSnacksKcal = $this->entriesRepository->SummSnacksKcal($datetime, $id, $meal1); // {{ snackcal|number_format }} 
    $SummBreakfast = $this->entriesRepository->SummBreakfast($datetime, $id, $meal2);  //  {{ breakcal|number_format }} 
    $SummLunch = $this->entriesRepository->SummLunch($datetime, $id, $meal3);         // {{ lunchkcal|number_format }} 
    $SummDinner = $this->entriesRepository->SummDinner($datetime, $id, $meal4);      // {{ dinnerkcal|number_format }}
    $SummTea = $this->entriesRepository->SummTea($datetime, $id, $meal5);           // {{ teakcal|number_format }}
    $SummSupper = $this->entriesRepository->SummSupper($datetime, $id, $meal6);    // {{ supperkcal|number_format }}

    return $this->render('User/Daily/index.html.twig', [
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
      'dataTest' => $datetime,
      ]
    );
  }
}
