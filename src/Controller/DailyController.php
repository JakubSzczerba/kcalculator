<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use App\Repository\ProductRepository;

class DailyController extends AbstractController
{
/**
   * @Route("/daily", name="daily")
   * @param ProductRepository $productRepository
   */
  public function daily()
  {
    $products = $productRepository->findAll();
    
    return $this->render('User/afterlogin.html.twig', [
      'controller_name' => 'DailyController',
      'products' => $products
    ]);
    
  }


/**
   * @Route("/findFood", name="findFood")
   * @param ProductRepository $productRepository
   */
  public function findFood()
  {
    $products = $productRepository->findProducts();

    return $this->render('User/searchproducts.html.twig',[
      'products' => $products
    ]);


  }


}

