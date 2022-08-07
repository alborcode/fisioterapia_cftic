<?php

namespace App\Form;

use App\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'Email',
                    'pattern' => '[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,63}$',
                    'class' => 'form-control',
                    'autofocus' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor Introduzca un correo',
                    ]),
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'attr' => [
                    'required' => true,
                    'label' => 'Terminos de uso',
                    'class' => 'my-3 mx-1',
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Tiene que aprobar los terminos de uso.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'placeholder' => 'Password',
                    'autocomplete' => 'new-password',
                    'class' => 'form-control',
                    'required' => true,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Por favor Introduzca una contraseña',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' =>
                            'Su contraseña debe tener al menos {{ limit }} caracteres',
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
