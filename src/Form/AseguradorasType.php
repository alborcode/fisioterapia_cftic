<?php

namespace App\Form;

use App\Entity\Aseguradoras;
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

class AseguradorasType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('nombre', TextType::class, [
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Nombre Aseguradora',
                    'pattern' => '[a-zA-Zç]{3,40}',
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>
                            'Por favor Introduzca un nombre de Aseguradora',
                    ]),
                    new Length([
                        'min' => 3,
                    ]),
                ],
            ])
            ->add('telefono', TelType::class, [
                'attr' => [
                    'required' => false,
                    'placeholder' => 'Telefono',
                    'pattern' =>
                        '(\+34|0034|34)?[ -]*(6|7)[ -]*([0-9][ -]*){8}',
                    'class' => 'form-control',
                ],
            ])
            ->add('direccion', TextType::class, [
                'attr' => [
                    'required' => false,
                    'placeholder' => 'Direccion',
                    'class' => 'form-control',
                ],
            ])
            ->add('codigopostal', TextType::class, [
                'attr' => [
                    'required' => false,
                    'placeholder' => 'Codigo Postal',
                    'class' => 'form-control',
                ],
            ])
            ->add('poblacion', TextType::class, [
                'attr' => [
                    'required' => false,
                    'placeholder' => 'Localidad',
                    'class' => 'form-control',
                ],
            ])
            ->add('provincia', TextType::class, [
                'attr' => [
                    'required' => false,
                    'placeholder' => 'Provincia',
                    'class' => 'form-control',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'required' => false,
                    'placeholder' => 'Email Contacto Aseguradora',
                    'pattern' => '^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$',
                    'class' => 'form-control',
                ],
            ]);
        // ->add('Enviar', SubmitType::class, [
        //     'attr' => [
        //         'class' => 'form-control',
        //     ],
        // ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aseguradoras::class,
        ]);
    }
}
