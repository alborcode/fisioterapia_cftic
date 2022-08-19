<?php

namespace App\Form;

use App\Entity\Informes;
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

class InformesType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('Fecha', DateType::class, [
                'attr' => [
                    'required' => true,
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
            ])
            ->add('tipoinforme', TextType::class, [
                'attr' => [
                    'placeholder' => 'Tipo Informe',
                    'pattern' => '[a-zA-Zç]{3,40}',
                    'class' => 'form-control',
                ],
            ])
            ->add('detalle')
            ->add('detalle', TextType::class, [
                'attr' => [
                    'placeholder' => 'Detalle',
                    'pattern' => '[a-zA-Z0-9ç-]{0,2000}',
                    'class' => 'form-control',
                ],
            ]);
        // ->add('anexo', FileType::class, [
        //     'label' => 'Brochure (PDF file)',
        //     'required' => false,
        //     'mapped' => false, // Se realizara control manual antes de guardar
        //     'constraints' => [
        //         new File([
        //             'maxSize' => '1024k',
        //             'mimeTypes' => ['application/pdf', 'application/x-pdf'],
        //             'mimeTypesMessage' =>
        //                 'Please upload a valid PDF document',
        //         ]),
        //     ],
        // ]);
        //->add('idfacultativo')
        //->add('idpaciente');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Informes::class,
        ]);
    }
}
