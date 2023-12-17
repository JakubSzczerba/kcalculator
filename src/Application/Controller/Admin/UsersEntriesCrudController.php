<?php

namespace Kcalculator\Application\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Kcalculator\Domain\Entry\Entity\UserEntry;


class UsersEntriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return UserEntry::class;
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
