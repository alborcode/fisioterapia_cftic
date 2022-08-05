<?php

namespace App\Form;

use App\Entity\Pacientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PacientesType extends AbstractType
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
            ->add('direccion', TextareaType::class)
            ->add('codigopostal')
            ->add('poblacion', TextareaType::class)
            ->add('provincia', TextareaType::class)
            ->add('idusuario');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacientes::class,
        ]);
    }
}
