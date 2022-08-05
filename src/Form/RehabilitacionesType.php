<?php

namespace App\Form;

use App\Entity\Rehabilitaciones;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RehabilitacionesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sesionestotales')
            ->add('sesionesrestantes')
            ->add('fechainicio')
            ->add('ultimasesion')
            ->add('observaciones')
            ->add('idpaciente')
            ->add('idaseguradora')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rehabilitaciones::class,
        ]);
    }
}
