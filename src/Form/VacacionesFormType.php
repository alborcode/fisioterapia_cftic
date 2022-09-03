<?php

namespace App\Form;

use App\Entity\Vacaciones;
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

class VacacionesFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('fecha', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'required' => true,
                    'class' => 'js-datepicker form-control',
                    'autofocus' => true,
                ],
            ])
            ->add('dianotrabajado', ChoiceType::class, [
                'choices' => [
                    'NO' => true,
                    'SI' => false,
                ],
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Marcada como Vacaciones',
                    'class' => 'form-control',
                ],
            ])
            ->add('diadebaja', ChoiceType::class, [
                'choices' => [
                    'NO' => true,
                    'SI' => false,
                ],
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Marcada como Baja',
                    'class' => 'form-control',
                ],
            ]);
        //->add('idfacultativo');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vacaciones::class,
        ]);
    }
}
