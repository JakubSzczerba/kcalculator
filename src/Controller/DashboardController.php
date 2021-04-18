<?php
declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DashboardController extends AbstractController
{
/**
   * @Route("/dashboard", name="dashboard")
   */
  public function dashboard()
  {
    return $this->render('Homepage/homeafterlog.html.twig', []);
  }

}
