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
            ->add('diasemanalunes', ChoiceType::class, [
                'choices' => [
                    'LUNES' => true,
                    'MARTES' => false,
                    'MIERCOLES' => false,
                    'JUEVES' => false,
                    'VIERNES' => false,
                    'SABADO' => false,
                    'DOMINGO' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('turnolunes', ChoiceType::class, [
                'choices' => [
                    'MAÑANA' => true,
                    'TARDE' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
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
            ->add('diasemanamartes', ChoiceType::class, [
                'choices' => [
                    'LUNES' => true,
                    'MARTES' => false,
                    'MIERCOLES' => false,
                    'JUEVES' => false,
                    'VIERNES' => false,
                    'SABADO' => false,
                    'DOMINGO' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('turnomartes', ChoiceType::class, [
                'choices' => [
                    'MAÑANA' => true,
                    'TARDE' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
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
            ->add('diasemanamiercoles', ChoiceType::class, [
                'choices' => [
                    'LUNES' => true,
                    'MARTES' => false,
                    'MIERCOLES' => false,
                    'JUEVES' => false,
                    'VIERNES' => false,
                    'SABADO' => false,
                    'DOMINGO' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('turnomiercoles', ChoiceType::class, [
                'choices' => [
                    'MAÑANA' => true,
                    'TARDE' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
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
            ->add('diasemanajueves', ChoiceType::class, [
                'choices' => [
                    'LUNES' => true,
                    'MARTES' => false,
                    'MIERCOLES' => false,
                    'JUEVES' => false,
                    'VIERNES' => false,
                    'SABADO' => false,
                    'DOMINGO' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('turnojueves', ChoiceType::class, [
                'choices' => [
                    'MAÑANA' => true,
                    'TARDE' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
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
            ->add('diasemanaviernes', ChoiceType::class, [
                'choices' => [
                    'LUNES' => true,
                    'MARTES' => false,
                    'MIERCOLES' => false,
                    'JUEVES' => false,
                    'VIERNES' => false,
                    'SABADO' => false,
                    'DOMINGO' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('turnoviernes', ChoiceType::class, [
                'choices' => [
                    'MAÑANA' => true,
                    'TARDE' => false,
                ],
                'attr' => [
                    'disabled' => true,
                    'readonly' => true,
                    'class' => 'form-control',
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
