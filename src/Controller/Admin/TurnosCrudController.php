<?php

namespace App\Controller\Admin;

use App\Entity\Turnos;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class TurnosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Turnos::class;
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
