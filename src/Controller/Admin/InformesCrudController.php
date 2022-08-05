<?php

namespace App\Controller\Admin;

use App\Entity\Informes;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class InformesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Informes::class;
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
