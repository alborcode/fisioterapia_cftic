<?php

namespace App\Controller\Admin;

use App\Entity\Pacientes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PacientesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Pacientes::class;
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
