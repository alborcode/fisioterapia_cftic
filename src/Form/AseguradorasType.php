<?php

namespace App\Form;

use App\Entity\Aseguradoras;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AseguradorasType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('nombre', TextareaType::class)
            ->add('telefono', TextareaType::class)
            ->add('direccion', TextareaType::class)
            ->add('codigopostal')
            ->add('poblacion', TextareaType::class)
            ->add('provincia', TextareaType::class)
            ->add('email');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aseguradoras::class,
        ]);
    }
}
