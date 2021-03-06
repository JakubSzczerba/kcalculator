<?php

namespace App\Controller\Admin;

use App\Entity\UserPreferention;
use App\Entity\User;
use App\Entity\UsersEntries;
use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(CrudUrlGenerator::class)->build();

        return $this->redirect($routeBuilder->setController(UserCrudController::class)->generateUrl());
      
    }

    public function configureMenuItems(): iterable

    {
        yield MenuItem::section('Menu');
        
        yield MenuItem::linkToCrud('User', 'icon class', User::class);
        yield MenuItem::linkToCrud('UserPreferention', 'icon class', UserPreferention::class);
        yield MenuItem::linkToCrud('UsersEntries', 'icon class', UsersEntries::class);
        yield MenuItem::linkToCrud('Products', 'icon class', Products::class);
        



    } 

    
}
