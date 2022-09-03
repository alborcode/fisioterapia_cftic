<?php

namespace App\Form;

use App\Entity\Turnos;
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

class TurnosType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder->add('diasemana', ChoiceType::class, [
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
                'placeholder' => 'Seleccione Dia de la Semana',
                'required' => true,
                'autofocus' => true,
                'class' => 'form-control',
            ],
        ]);
        // ->add('horainicio', TimeType::class, [
        //     'attr' => [
        //         'required' => true,
        //         'class' => 'form-control',
        //     ],
        // ])
        // ->add('horafin', TimeType::class, [
        //     'attr' => [
        //         'required' => true,
        //         'class' => 'form-control',
        //     ],
        // ])
        //->add('idfacultativo');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Turnos::class,
        ]);
    }
}
