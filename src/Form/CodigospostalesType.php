<?php

namespace App\Form;

use App\Entity\Codigospostales;
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

class CodigospostalesType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('codigopostal', TextType::class, [
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Codigo Postal',
                    'pattern' => '[0-9]{5,5}',
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>
                            'Por favor Introduzca un codigo postal correcto',
                    ]),
                ],
            ])
            ->add('poblacion', TextType::class, [
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Localidad',
                    'pattern' => '[a-zA-Zç]{3,40}',
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' =>
                            'Por favor Introduzca un nombre de Localidad',
                    ]),
                    new Length([
                        'min' => 3,
                    ]),
                ],
            ])
            ->add('provincia', EntityType::class, [
                'class' => Provincias::class,
                'choice_label' => 'nombre',
                'attr' => [
                    'required' => true,
                    'placeholder' => 'Provincia',
                    'class' => 'form-control',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Codigospostales::class,
        ]);
    }
}
