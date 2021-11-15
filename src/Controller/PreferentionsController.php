<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use App\Form\PreferentionsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\UserPreferention;
use App\Entity\UserWeightHistory;

class PreferentionsController extends AbstractController
{
  private EntityManagerInterface $entityManager;

  public function __construct(
    EntityManagerInterface $entityManager
  ) {
    $this->entityManager = $entityManager;  
  }

  /**
   * @Route("/preferention", methods="POST", name="preferention")
   */
  public function setPreferention(Request $request)
  {
    /** Reulst before fill from */
    $result = 0;

    /** Daily caloric requirement per user for */
    $caloric_requirement = 0;

    /** requirement Kcal according to established gains */
    $kcal_day = 0; 
    
    /** Value connected with the chosen intents */
    $burn = -300;           
    $keep = 0;              
    $gain = 300;            

    /** Variable connected with amount of macronutrients per day - beefore calculating  */
    $protein = 1;
    $fat = 1;
    $carbo = 1;

    $form = $this->createForm(PreferentionsType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) 
    {
      $gender = $form->get('gender')->getData();
      $weight = $form->get('weight')->getData();
      $height = $form->get('height')->getData();
      $age = $form->get('age')->getData();
      $activity = $form->get('activity')->getData();
      $intentions = $form->get('intentions')->getData();

      if ($gender == 'man')
      {
        $gender = 'Mężczyzna';
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif ($gender == 'woman')
      {
        $gender = 'Kobieta';
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }  

      if ($activity == 'activity1')
      {
        $activity = 'niską aktywność w ciągu dnia';     //low activity
        $result = $result * 1.45;      
      }
      elseif ($activity == 'activity2')
      {
        $activity = 'średnią aktywność w ciągu dnia';   //medium activity
        $result = $result * 1.75;    
      }
      elseif ($activity == 'activity3')
      {
        $activity = 'wysoką aktywność w ciągu dnia';    //high activity
        $result = $result * 2.0;        
      } 
      
      settype($result, "integer");
      $caloric_requirement = $caloric_requirement + $result;

      if ($intentions == 'intension1')
      {
        $intentions = 'zredukować tkankę tłuszczową';
        $kcal_day = ($caloric_requirement + $burn);

        /** Macronutrients for burning fat */
        $protein = 2 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($intentions == 'intension2')
      {
        $intentions = 'utrzymać masę ciała';
        $kcal_day = ($caloric_requirement + $keep);

        /** Macronutrients for keep weight */
        $protein = 1.60 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($intentions == 'intension3')
      {
        $intentions = 'zbudować masę mięśniową';
        $kcal_day = ($caloric_requirement + $gain);

        /** Macronutrients for build muscle */
        $protein = 1.85 * $weight; 
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      } 
      
      /** Changing type from few number after comma for just integer */
      settype($kcal_day, "integer");
      settype($protein, "integer");
      settype($fat, "integer");
      settype($carbo, "integer");
     
      /** Seting all preferentions from datas */
      $preferention = new UserPreferention();
      $preferention->setGender($gender);
      $preferention->setWeight($weight);
      $preferention->setHeight($height);
      $preferention->setAge($age);
      $preferention->setActivity($activity);
      $preferention->setKcal($caloric_requirement);
      $preferention->setIntentions($intentions);
      $preferention->setKcalDay($kcal_day);
      $preferention->setProteinPerDay($protein);
      $preferention->setFatPerDay($fat);
      $preferention->setCarboPerDay($carbo);
      $preferention->setUsers($this->getUser());

      /** Creating a Weight History for geting data to chart on Dashboard */
      $userWeightHistory = new UserWeightHistory();
      $userWeightHistory->setUsers($this->getUser());
      $userWeightHistory->setUserWeight($weight);
      $userWeightHistory->setDateTime(new \DateTime());

      $this->entityManager->persist($preferention);
      $this->entityManager->persist($userWeightHistory);
      $this->entityManager->flush();

      return $this->redirectToRoute('dashboard');
    }

    return $this->render('User/Preferentions/index.html.twig', [
      'form' => $form->createView()
    ]);
  }

  /**
   * @Route("/preferention/{id}/edit", methods="GET|POST", name="editPreferentions")
   */
  public function editPreferentions(int $id, Request $request)
  {
    $preferention = $this->getDoctrine()->getRepository(UserPreferention::class)->find(array('id' => $id,));

    $form = $this->createForm(PreferentionsType::class, $preferention);
    $form->handleRequest($request);

    /** Logic from setPreferention */

    $result = 0;
    $caloric_requirement = 0;
    $kcal_day = 0; 
    $burn = -300;           
    $keep = 0;              
    $gain = 300;
    $protein = 1;
    $fat = 1;
    $carbo = 1;

    if ($form->isSubmitted() && $form->isValid())
    {
      $gender = $form->get('gender')->getData();
      $weight = $form->get('weight')->getData();
      $height = $form->get('height')->getData();
      $age = $form->get('age')->getData();
      $activity = $form->get('activity')->getData();
      $intentions = $form->get('intentions')->getData();

      if ($gender == 'man')
      {
        $gender = 'Mężczyzna';
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif ($gender == 'woman')
      {
        $gender = 'Kobieta';
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }  

      if ($activity == 'activity1')
      {
        $activity = 'niską aktywność w ciągu dnia';     //low activity
        $result = $result * 1.45;      
      }
      elseif ($activity == 'activity2')
      {
        $activity = 'średnią aktywność w ciągu dnia';   //medium activity
        $result = $result * 1.75;    
      }
      elseif ($activity == 'activity3')
      {
        $activity = 'wysoką aktywność w ciągu dnia';    //high activity
        $result = $result * 2.0;        
      } 
      
      settype($result, "integer");
      $caloric_requirement = $caloric_requirement + $result;

      if ($intentions == 'intension1')
      {
        $intentions = 'zredukować tkankę tłuszczową';
        $kcal_day = ($caloric_requirement + $burn);

        /** Macronutrients for burning fat */
        $protein = 2 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($intentions == 'intension2')
      {
        $intentions = 'utrzymać masę ciała';
        $kcal_day = ($caloric_requirement + $keep);

        /** Macronutrients for keep weight */
        $protein = 1.60 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($intentions == 'intension3')
      {
        $intentions = 'zbudować masę mięśniową';
        $kcal_day = ($caloric_requirement + $gain);

        /** Macronutrients for build muscle */
        $protein = 1.85 * $weight; 
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      } 
      
      /** Changing type from few number after comma for just integer */
      settype($kcal_day, "integer");
      settype($protein, "integer");
      settype($fat, "integer");
      settype($carbo, "integer");

      $preferention->setGender($gender);
      $preferention->setWeight($weight);
      $preferention->setHeight($height);
      $preferention->setAge($age);
      $preferention->setActivity($activity);
      $preferention->setKcal($caloric_requirement);
      $preferention->setIntentions($intentions);
      $preferention->setKcalDay($kcal_day);
      $preferention->setProteinPerDay($protein);
      $preferention->setFatPerDay($fat);
      $preferention->setCarboPerDay($carbo);

      $userWeightHistory = new UserWeightHistory();
      $userWeightHistory->setUsers($this->getUser());
      $userWeightHistory->setUserWeight($weight);
      $userWeightHistory->setDateTime(new \DateTime());

      $this->entityManager->persist($userWeightHistory);
      $this->entityManager->flush();

      return $this->redirectToRoute('dashboard');
    }

    return $this->render('User/Preferentions/index.html.twig', [
      'form' => $form->createView()
    ]);
  }
} 
