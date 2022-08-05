<?php

namespace App\Form;

use App\Entity\Turnos;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurnosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('fecha')
            ->add('horainicio')
            ->add('horafin')
            ->add('turno')
            ->add('disponible')
            ->add('idfacultativo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Turnos::class,
        ]);
    }
}
