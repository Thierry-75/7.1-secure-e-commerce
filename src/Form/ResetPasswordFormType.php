<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Sequentially;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class ResetPasswordFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('password', RepeatedType::class, [
            'type' => PasswordType::class,
            'options' => [
                'attr' => [
                    'class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                    focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                     dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-3',
                    'required' => true,
                    'autocomplete' => 'new-password',
                    'placeholder' => ''
                ],
                'label' => 'Nouveau mot de passe',
                'label_attr' => ['class' => 'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400']
            ],
            'first_options' => [
                'constraints' => [
                    new Sequentially([
                        new NotBlank(['message' => 'Indiquez votre mot de passe']),
                        new Length([
                            'min' => 10,
                            'max' => 12,
                            'minMessage' => 'Minimum {{ limit }} caractères',
                            'maxMessage' => 'Maximum {{ limit }} caractères'
                        ]),
                        new Regex(
                            pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{10,12}$/i',
                            htmlPattern: '^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$ %^&*-]).{10,12}$'
                        )
                    ])
                ],

            ],
            'second_options' => [
                'label' => 'Répètez votre mot de passe',
            ],
            'invalid_message' => 'Les champs sont différents.',
            // Instead of being set onto the object directly,
            // this is read and encoded in the controller
            'mapped' => false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
