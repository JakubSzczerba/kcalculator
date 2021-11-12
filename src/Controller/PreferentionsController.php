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
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Entity\UserPreferention;
use App\Entity\UserWeightHistory;

class PreferentionsController extends AbstractController
{
/**
   * @Route("/preferentions", name="preferentions")
   */
  public function preferentions()
  {
    return $this->render('User/preferentions.html.twig');
  }

  /**
   * @Route("/preferentions/edit", name="preferentionsEdit")
   */
  public function editreferentions()
  {
    return $this->render('User/editPreferentions.html.twig');
  }

/**
   * @Route("/setPreferention", methods="POST", name="setPreferention")
   */
  public function setPreferention(Request $request, EntityManagerInterface $entityManager): Response
  {
    /** Reulst before fill from */
    $result = 0;

    /** Daily caloric requirement per user for */
    $caloric_requirement = 0;

    /** requirement Kcal according to established gains */
    $kcal_day = 0;           
  
    /** Form variables (selecting by user) */
    $gender = '';
    $weight = $request->get('weight'); //$request->get('man');
    $height = $request->get('height');
    $age =$request->get('age');
    $activity = '';
    $intentions = '';

    /** Value connected with the chosen intents */
    $burn = -300;           
    $keep = 0;              
    $gain = 300;            

    /** Variable connected with amount of macronutrients per day - beefore calculating  */
    $protein = 1;
    $fat = 1;
    $carbo = 1;
    
    if ( !empty($request->get('man')) || !empty($request->get('woman')) )
    {
      if ($request->get('man'))
      {
        $gender = 'Mężczyzna';
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif ($request->get('woman'))
      {
        $gender = 'Kobieta';
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }  
    }

    if ( !empty($request->get('activity1')) || !empty($request->get('activity2')) || !empty($request->get('activity3')) )
    {
      if ($request->get('activity1'))
      {
        $activity = 'niską aktywność w ciągu dnia';     //low activity
        $result = $result * 1.45;      
      }
      elseif ($request->get('activity2'))
      {
        $activity = 'średnią aktywność w ciągu dnia';   //medium activity
        $result = $result * 1.75;    
      }
      elseif ($request->get('activity3'))
      {
        $activity = 'wysoką aktywność w ciągu dnia';    //high activity
        $result = $result * 2.0;        
      }      
    } 

    settype($result, "integer");
    $caloric_requirement = $caloric_requirement + $result;
    
    if ( !empty($request->get('intension1')) || !empty($request->get('intension2')) || !empty($request->get('intension3')) )
    {
      if ($request->get('intension1'))
      {
        $intentions = 'zredukować tkankę tłuszczową';
        $kcal_day = ($caloric_requirement + $burn);

        /** Macronutrients for burning fat */
        $protein = 2 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($request->get('intension2'))
      {
        $intentions = 'utrzymać masę ciała';
        $kcal_day = ($caloric_requirement + $keep);

        /** Macronutrients for keep weight */
        $protein = 1.60 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($request->get('intension3'))
      {
        $intentions = 'zbudować masę mięśniową';
        $kcal_day = ($caloric_requirement + $gain);

        /** Macronutrients for build muscle */
        $protein = 1.85 * $weight; 
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }      
    } 
    
    /** Changing type from few number after comma for just integer */
    settype($kcal_day, "integer");
    settype($protein, "integer");
    settype($fat, "integer");
    settype($carbo, "integer");

    /** Seting all preferentions */
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

    $entityManager->persist($preferention);
    $entityManager->persist($userWeightHistory);
    $entityManager->flush();

    return $this->render('User/loadedpreferentions.html.twig', [
      'preferentions' => $preferention
      ]
    );      
  }

  /**
   * @Route("/editPreferention/{id}", methods="GET|POST", name="editPreferention")
   */
  public function editPreferention(Request $request, int $id, EntityManagerInterface $entityManager): Response
  {
    $preferention = new UserPreferention();
    $preferention = $this->getDoctrine()->getRepository(UserPreferention::class)->find(array('id' => $id,));

    /* Logic from setPreferentions method */
    $result = 0;
    $caloric_requirement = 0;  
    $kcal_day = 0;              
  
    $gender = $preferention->getGender();
    $weight = $preferention->getWeight(); 
    $height = $preferention->getHeight();
    $age = $preferention->getAge();
    $activity = $preferention->getActivity();
    $intentions = $preferention->getIntentions();

    /** Value connected with the chosen intents */
    $burn = -300;           
    $keep = 0;              
    $gain = 300;    

    /** Variable connected with amount of macronutrients per day - beefore calculating  */
    $protein = 1;
    $fat = 1;
    $carbo = 1;

    if ( !empty($request->get('weight')) || !empty($request->get('height')) || !empty($request->get('age')) )
    {
      $weight = $request->get('weight');
      $height = $request->get('height');
      $age = $request->get('age');
    }

    if ( !empty($request->get('man')) || !empty($request->get('woman')) )
    {
      if ($request->get('man'))
      {
        $gender = 'Mężczyzna';
        $result = (10*$weight) + (6.25*$height) - (5*$age) + 5 ;
      } 
      elseif ($request->get('woman'))
      {
        $gender = 'Kobieta';
        $result = (10*$weight) + (6.25*$height) - (5*$age) - 161 ;
      }  
    }

    if ( !empty($request->get('activity1')) || !empty($request->get('activity2')) || !empty($request->get('activity3')) )
    {
      if ($request->get('activity1'))
      {
        $activity = 'niską aktywność w ciągu dnia';     //low activity
        $result = $result * 1.45;      
      }
      elseif ($request->get('activity2'))
      {
        $activity = 'średnią aktywność w ciągu dnia';   //medium activity
        $result = $result * 1.75;    
      }
      elseif ($request->get('activity3'))
      {
        $activity = 'wysoką aktywność w ciągu dnia';    //high activity
        $result = $result * 2.0;        
      }      
    } 

    settype($result, "integer");
    $caloric_requirement = $caloric_requirement + $result;

    if ( !empty($request->get('intension1')) || !empty($request->get('intension2')) || !empty($request->get('intension3')) )
    {
      if ($request->get('intension1'))
      {
        $intentions = 'zredukować tkankę tłuszczową';
        $kcal_day = ($caloric_requirement + $burn);

        /** Macronutrients for burning fat */
        $protein = 2 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($request->get('intension2'))
      {
        $intentions = 'utrzymać masę ciała';
        $kcal_day = ($caloric_requirement + $keep);

        /** Macronutrients for keep weight */
        $protein = 1.60 * $weight;
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }
      elseif ($request->get('intension3'))
      {
        $intentions = 'zbudować masę mięśniową';
        $kcal_day = ($caloric_requirement + $gain);

        /** Macronutrients for build muscle */
        $protein = 1.85 * $weight; 
        $fat = ($kcal_day * 0.25)/9 ;
        $carbo = ($kcal_day - ($protein * 4) - ($fat * 9) )/4 ;
      }      
    } 

    /** Changing type from few number after comma for just integer */
    settype($kcal_day, "integer");
    settype($protein, "integer");
    settype($fat, "integer");
    settype($carbo, "integer");
    
    /** Seting all preferentions */
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

    /** Creating a Weight History for geting data to chart on Dashboard */
    $userWeightHistory = new UserWeightHistory();
    $userWeightHistory->setUsers($this->getUser());
    $userWeightHistory->setUserWeight($weight);
    $userWeightHistory->setDateTime(new \DateTime());

    $entityManager = $this->getDoctrine()->getManager();
    $entityManager->persist($userWeightHistory);
    $entityManager->flush();

    if (isset($_POST['editpref']))
    {
      return $this->redirectToRoute('profile');
    } 

    else {
      return $this->render('User/editPreferentions.html.twig', [
        'preferention' => $preferention,
        ]
      ); 
    }
  }

  /**
   * @Route("/testPref", name="testPref")
   */
  public function testPref(Request $request, EntityManagerInterface $entityManager)
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

      $entityManager->persist($preferention);
      $entityManager->flush();
    }

    return $this->render('User/testPref.html.twig', [
      'form' => $form->createView()
    ]);
  }
} 
