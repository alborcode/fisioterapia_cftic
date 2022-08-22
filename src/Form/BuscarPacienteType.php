<?php

namespace App\Form;

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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuscarPacienteType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
