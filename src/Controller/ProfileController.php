<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use App\Repository\ProfileRepository;

class ProfileController extends AbstractController
{
    /**
   * @Route("/profile", name="profile")
   */
  public function showProfile(ProfileRepository $profileRepository)
  
  {
    $id = $this->getUser()->getId();
    $profile = $profileRepository->userProfile($id);

    return $this->render('User/Profile/index.html.twig', [
        'profile' => $profile
      ]
    );
  }
}
