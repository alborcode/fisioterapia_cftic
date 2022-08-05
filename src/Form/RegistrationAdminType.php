<?php

namespace App\Form;

use App\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationAdminType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Paciente' => 'ROLE_PACIENTE',
                    'Facultativo' => 'ROLE_FACULTATIVO',
                    'Administrador' => 'ROLE_ADMINISTRADOR',
                ],
                'expanded' => true,
                'multiple' => true,
                'data' => $entity->getRoles(), // Current roles assigned..
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' =>
                            'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
