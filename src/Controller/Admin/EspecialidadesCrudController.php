<?php

namespace App\Controller\Admin;

use App\Entity\Especialidades;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class EspecialidadesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Especialidades::class;
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
