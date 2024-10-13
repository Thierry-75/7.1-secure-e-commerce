<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Sequentially;

class ResetPasswordRequestFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email', EmailType::class, [
            'attr' => [
                'class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
          focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
           dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 my-3',
                'required' => true
            ],
            'label' => 'Email',
            'label_attr' => ['class' => 'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
            'constraints' => [
                new Sequentially([
                    new NotBlank(message: "indiquez votre adresse email"),
                    new Length(['max' => 180, 'maxMessage' => '180 caractères autorisés']),
                    new Email(message: '{{ value }} n\'est pas un email valide.')
                ])
            ]
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
