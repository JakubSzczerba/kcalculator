<?php

/*
 * This file was created by Jakub Szczerba
 * It is part of an engineering project - Kcalculator - copyright is reserved
 * Contact: https://www.linkedin.com/in/jakub-szczerba-3492751b4/
*/

declare(strict_types=1);

namespace Kcalculator\Controller\Admin;

use Kcalculator\Entity\UserPreferention;
use Kcalculator\Entity\User;
use Kcalculator\Entity\UsersEntries;
use Kcalculator\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class AdminDashboardController extends AbstractDashboardController
{
    private AdminUrlGenerator $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator
            ->setController(UserCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
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
