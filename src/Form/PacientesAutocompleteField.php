<?php

namespace App\Form;

use App\Entity\Pacientes;
use App\Repository\PacientesRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class PacientesAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Pacientes::class,
            'placeholder' => 'Choose a Pacientes',
            //'choice_label' => 'name',

            'query_builder' => function(PacientesRepository $pacientesRepository) {
                return $pacientesRepository->createQueryBuilder('pacientes');
            },
            //'security' => 'ROLE_SOMETHING',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
