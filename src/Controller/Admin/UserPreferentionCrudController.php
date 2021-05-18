<?php

namespace App\Controller\Admin;

use App\Entity\UserPreferention;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserPreferentionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserPreferention::class;
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
