<?php

namespace App\Controller\Admin;

use App\Entity\UsersEntries;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;


class UsersEntriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UsersEntries::class;
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
