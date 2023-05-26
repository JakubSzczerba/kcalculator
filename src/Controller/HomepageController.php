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
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function homepage(): Response
    {       
        if ($this->getUser()) 
        {
            return $this->redirectToRoute('dashboard');         
        } 

        else {
            return $this->render('Homepage/homepage.html.twig');
        }         
    }
}
