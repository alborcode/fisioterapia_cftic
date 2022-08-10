<?php

namespace App\Form;

use App\Entity\Rehabilitaciones;
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

class RehabilitacionesType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('sesionestotales', NumberType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('sesionesrestantes', NumberType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('fechainicio', DateType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('ultimasesion', DateTimeType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('observaciones', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Texto del informe',
                    'class' => 'form-control',
                ],
            ]);
        //->add('idpaciente')
        //->add('idaseguradora');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rehabilitaciones::class,
        ]);
    }
}
