<?php

namespace App\Form;

use App\Entity\Citas;
use App\Entity\Especialidades;
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

class CitasType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('Especialidad', EntityType::class, [
                'class' => Especialidades::class,
                'choice_label' => 'nombre',
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
            ])
            //->add('idfacultativo')
            ->add('Nombre Facultativo', EntityType::class, [
                'class' => Facultativos::class,
                'choice_label' => 'nombre',
                'query_builder' => function (EntityRepository $er) {
                    return $er
                        ->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC');
                },
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('Fecha', DateType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ])
            ->add('Hora', TimeType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                ],
            ]);

        //->add('disponible')

        //->add('idpaciente');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Citas::class,
        ]);
    }
}
