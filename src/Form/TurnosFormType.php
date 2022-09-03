<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TurnosFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('idfacultativo', [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('nombre', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('apellido1', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('apellido2', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('telefono', TelType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('especialidad', EntityType::class, [
                'class' => Especialidades::class,
                'choice_label' => 'especialidad',
                'choice_value' => 'especialidad',
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            // LUNES
            ->add('diasemanalunes', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                    'value' => 'LUNES',
                ],
            ])
            ->add('horainiciolunes', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('horafinlunes', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ])
            // MARTES
            ->add('diasemanamartes', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                    'value' => 'MARTES',
                ],
            ])
            ->add('horainiciomartes', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            ->add('horafinmartes', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            // MIERCOLES
            ->add('diasemanamiercoles', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                    'value' => 'MIERCOLES',
                ],
            ])
            ->add('horainiciomiercoles', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            ->add('horafinmiercoles', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            // JUEVES
            ->add('diasemanajueves', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                    'value' => 'LUNES',
                ],
            ])
            ->add('horainiciojueves', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            ->add('horafinjueves', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            // VIERNES
            ->add('diasemanaviernes', TextType::class, [
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                    'value' => 'LUNES',
                ],
            ])
            ->add('horainicioviernes', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ])
            ->add('horafinviernes', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'min' => '14:00:00',
                    'max' => '20:00:00',
                    'value' => '14:00:00',
                    'step' => '3600',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
