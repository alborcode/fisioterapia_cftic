<?php

namespace App\Form;

use App\Entity\Facultativos;
use App\Entity\Especialidades;
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

class PerfilFacultativoType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('idusuario', NumberType::class, [
                'attr' => [
                    'pattern' => '[0-9]{1,11}',
                    'class' => 'form-control',
                    'required' => false,
                    'disabled' => true,
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                    'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$',
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor Introduzca un correo',
                    ]),
                    new Length(['min' => 3]),
                ],
            ])
            ->add('idfacultativo', NumberType::class, [
                'attr' => [
                    'pattern' => '[0-9]{1,11}',
                    'class' => 'form-control',
                    'required' => false,
                    'disabled' => true,
                ],
            ])
            ->add('nombre', TextType::class, [
                'attr' => [
                    'placeholder' => 'Nombre',
                    'pattern' => '[a-zA-Z-\'áéíóúüÁÉÍÓÚÜ]{3,40}',
                    'class' => 'form-control',
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
            ->add('especialidad', EntityType::class, [
                'class' => Especialidades::class,
                'choice_label' => 'especialidad',
                'choice_value' => 'especialidad',
                'data_class' => null,
                'empty_data' => '',
                'attr' => [
                    'placeholder' => 'Seleccione Especialidad',
                    'required' => true,
                    'class' => 'form-control',
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
