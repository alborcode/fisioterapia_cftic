<?php

namespace App\Form;

use App\Entity\Informes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformesType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('fecha')
            ->add('tipoinforme')
            ->add('detalle')
            ->add('anexo', FileType::class, [
                'label' => 'Brochure (PDF file)',
                'required' => false,
                'mapped' => false, // Se realizara control manual antes de guardar
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['application/pdf', 'application/x-pdf'],
                        'mimeTypesMessage' =>
                            'Please upload a valid PDF document',
                    ]),
                ],
            ])
            ->add('idfacultativo')
            ->add('idpaciente');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Informes::class,
        ]);
    }
}
