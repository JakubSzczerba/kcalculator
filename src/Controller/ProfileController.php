<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Kcalculator\Repository\ProfileRepository;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function showProfile(ProfileRepository $profileRepository): Response

    {
        $id = $this->getUser()->getId();
        $profile = $profileRepository->userProfile($id);

        return $this->render('User/Profile/index.html.twig', [
                'profile' => $profile
            ]
        );
    }
}
