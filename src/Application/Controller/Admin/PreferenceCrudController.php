<?php

namespace Kcalculator\Application\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Kcalculator\Domain\Preference\Entity\Preference;

class PreferenceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Preference::class;
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
