<?php

namespace App\Controller\Admin;

use App\Entity\DataTime;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class DataTimeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return DataTime::class;
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
