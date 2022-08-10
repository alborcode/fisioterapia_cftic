<?php

namespace App\Form;

use App\Entity\Pacientes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\IsTrue;

class PacientesType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('nombre', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nombre',
                    'pattern' => '[a-zA-Zç]{3,40}',
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor Introduzca un nombre',
                    ]),
                    new Length(['min' => 3]),
                ],
            ])
            ->add('apellido1', TextType::class, [
                'attr' => [
                    'placeholder' => 'Primer Apellido',
                    'pattern' => '[a-zA-Z-\']{3,40}',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor Introduzca un Apellido',
                    ]),
                    new Length(['min' => 3]),
                ],
            ])
            ->add('apellido2', TextType::class, [
                'attr' => [
                    'placeholder' => 'Segundo Apellido',
                    'class' => 'form-control',
                ],
            ])
            ->add('telefono', TelType::class, [
                'attr' => [
                    'placeholder' => 'Telefono',
                    'pattern' =>
                        '(\+34|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}',
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor Introduzca un Telefono',
                    ]),
                ],
            ])
            ->add('direccion', TextType::class, [
                'attr' => [
                    'placeholder' => 'Direccion',
                    'class' => 'form-control',
                ],
            ])
            ->add('codigopostal', TextType::class, [
                'attr' => [
                    'placeholder' => 'Codigo Postal',
                    'class' => 'form-control',
                ],
            ])
            ->add('poblacion', TextType::class, [
                'attr' => [
                    'placeholder' => 'Localidad',
                    'class' => 'form-control',
                ],
            ])
            ->add('provincia', TextType::class, [
                'attr' => [
                    'placeholder' => 'Provincia',
                    'class' => 'form-control',
                ],
            ]);
        //->add('idusuario');
        // ->add('Alta Paciente', SubmitType::class, [
        //     'attr' => [
        //         'class' => 'form-control',
        //     ],
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Pacientes::class,
        ]);
    }
}
