<?php

namespace App\Form;

use App\Entity\Pacientes;
use App\Entity\Provincias;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
                    'pattern' => '[a-zA-Z-\'áéíóúüÁÉÍÓÚÜ]{3,40}',
                    'class' => 'form-control',
                    'autofocus' => true,
                    'required' => true,
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
                    'placeholder' => 'Apellido',
                    'pattern' => '[a-zA-Z-\'áéíóúüÁÉÍÓÚÜ]{3,40}',
                    'class' => 'form-control',
                    'required' => true,
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
                    'placeholder' => 'Apellido',
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
                    'required' => false,
                ],
            ])
            ->add('codigopostal', TextType::class, [
                'attr' => [
                    'placeholder' => 'Codigo Postal',
                    'class' => 'form-control',
                    'required' => false,
                ],
            ])
            ->add('poblacion', TextType::class, [
                'attr' => [
                    'placeholder' => 'Localidad',
                    'class' => 'form-control',
                    'required' => false,
                ],
            ])
            ->add('provincia', EntityType::class, [
                'class' => Provincias::class,
                'choice_label' => 'provincia',
                'choice_value' => 'provincia',
                'data_class' => null,
                'empty_data' => '',
                //'required' => true,
                'attr' => [
                    'placeholder' => 'Seleccione Provincia',
                    'required' => false,
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
