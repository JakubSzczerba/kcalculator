<?php

namespace App\Controller\Admin;

use App\Entity\UserDaily;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserDailyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserDaily::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
