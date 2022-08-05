<?php

namespace App\Controller\Admin;

use App\Entity\Aseguradoras;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AseguradorasCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Aseguradoras::class;
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
