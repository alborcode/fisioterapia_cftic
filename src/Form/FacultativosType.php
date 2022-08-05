<?php

namespace App\Form;

use App\Entity\Facultativos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FacultativosType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('nombre', TextareaType::class)
            ->add('apellido1', TextareaType::class)
            ->add('apellido2', TextareaType::class)
            ->add('telefono', TextareaType::class)
            ->add('especialidad', EntityType::class, [
                'class' => Especialidades::class,
                'choice_label' => 'nombre',
            ])
            ->add('idusuario');
        // ->add('Save', SubmitType::class)
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facultativos::class,
        ]);
    }
}
