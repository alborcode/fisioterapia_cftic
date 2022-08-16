<?php

namespace App\Form;

use App\Entity\Facultativos;
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
            ->add('especialidad', EntityType::class, [
                'class' => Especialidades::class,
                'choice_label' => 'nombre',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Especialidad',
                    'pattern' => '[a-zA-Zç]{3,60}',
                    'class' => 'form-control',
                ],
            ]);
        //->add('idusuario');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Facultativos::class,
        ]);
    }
}