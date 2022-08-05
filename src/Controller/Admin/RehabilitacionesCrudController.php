<?php

namespace App\Controller\Admin;

use App\Entity\Rehabilitaciones;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RehabilitacionesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Rehabilitaciones::class;
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
