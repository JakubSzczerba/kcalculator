<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * Class LoginController
 * @package App\Controller
 */

class LoginController extends AbstractController 
{
  /**
   * @Route("/login", name="login")
   */
  public function login(AuthenticationUtils $authenticationUtils)
  {  
    $errors = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('User/Account/Login/index.html.twig', [
      'errors' => $errors,
      'username' => $lastUsername
      ]
    );
  }

  /**
   * @Route("/logout", name="logout")
   */
  public function logout() {}
}
