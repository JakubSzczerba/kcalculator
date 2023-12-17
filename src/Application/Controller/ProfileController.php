<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Application\Controller;

use Kcalculator\Infrastructure\Repository\ProfileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
