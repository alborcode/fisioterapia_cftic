<?php

namespace App\Controller\Admin;

use App\Entity\Facultativos;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class FacultativosCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Facultativos::class;
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
