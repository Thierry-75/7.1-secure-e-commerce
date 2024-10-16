<?php

namespace App\Form;

use App\Entity\Civility;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Sequentially;

use function PHPUnit\Framework\returnSelf;

class CivilityFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                'label'=>'Nom :',
                'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                'constraints' => [
                    new Sequentially([
                        new NotBlank(message: ''),
                        new Length(['min' => 2, 'max' =>30, 'minMessage'=>'minimum 2 lettres', 'maxMessage'=>'30' ]),
                        new Regex(
                            pattern:'/^[a-zA-Z- \'éèçï]{2,30}$/i',
                            htmlPattern: '^[a-zA-Z- \'éèçï]{2,30}$'
                        )
                    ])
                ]
                   ])
            ->add('prenom',TextType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                   'label'=>'Prénom :',
                   'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                   'constraints' => [
                    new Sequentially([
                        new NotBlank(message: ''),
                        new Length(['min' => 2, 'max' =>30, 'minMessage'=>'minimum 2 lettres', 'maxMessage'=>'30' ]),
                        new Regex(
                            pattern:'/^[a-zA-Z- \'éèçï]{2,30}$/i',
                            htmlPattern: '^[a-zA-Z- \'éèçï]{2,30}$'
                        )
                    ])
                ]
                   ])
            ->add('telephone',TelType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                   'label'=>'Téléphone :',
                   'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                   'constraints'=>[
                        new NotBlank(message: ''),
                        new Length(['min'=>10,'max'=>10, 'minMessage'=>'10 chiffres sans espace', 'maxMessage'=>'10 chiffres sans espace']),
                        new Regex(
                            pattern:'/^[0-9]{10}$/',
                            htmlPattern:'^[0-9]{10}$'
                        )
                   ]
                   ])
            ->add('numero',NumberType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                   'label'=>'Numéro de rue :',
                   'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                   'constraints'=>[
                    new NotBlank(message: ''),
                    new Length(['min'=>1,'max'=> 4,'minMessage'=>'Minimum 1 chiffres','maxMessage'=>'maximum 4 chiffres']),
                    new Regex(
                        pattern:'/^[0-9]{1,4}$/',
                        htmlPattern:'^[0-9]{1,4}$'
                    )
               ]
                   ])
            ->add('adresse',TextType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                   'label'=>'Adresse :',
                   'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                   'constraints' => [
                    new Sequentially([
                        new NotBlank(message: ''),
                        new Length(['min' => 2, 'max' =>100, 'minMessage'=>'minimum 2 lettres', 'maxMessage'=>'maximum 100 lettres' ]),
                        new Regex(
                            pattern: '/^[a-zA-Z- \'éèçïàôùê]{2,100}$/i',
                            htmlPattern: '^[a-zA-Z- \'éèçï]{2,100}$'
                        )
                    ])
                ]])
            ->add('codePostal',TextType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                   'label'=>'Code postal :',
                   'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                   'constraints'=>[
                    new NotBlank(message: ''),
                    new Length(['min'=>5, 'max'=>5,'minMessage'=>'5 chiffres','maxMessage'=>'5 chiffres']),
                    new Regex(
                        pattern:'/^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$/',
                        htmlPattern:'^(?:0[1-9]|[1-8]\d|9[0-8])\d{3}$'
                    )
               ]
                   ])
            ->add('ville',TextType::class,[
                'attr' => ['class' => 'rounded-lg bg-gray-50 border border-gray-300 text-gray-900 text-xs 
                  focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700
                   dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500', 'placeholder' => ''],
                   'label'=>'Ville :',
                   'label_attr'=>['class'=>'block mb-1 text-xs font-light text-gray-500 dark:text-gray-400'],
                   'constraints' => [
                    new Sequentially([
                        new NotBlank(message: ''),
                        new Length(['min' => 2, 'max' =>50, 'minMessage'=>'minimum 2 lettres', 'maxMessage'=>'maximum 50 lettres' ]),
                        new Regex(
                            pattern: '/^[a-zA-Z- \'éèçïàôùê]{2,50}$/i',
                            htmlPattern: '^[a-zA-Z- \'éèçïàôùê]{2,50}$'
                        )
                    ])
                ]
                   ])
            ->add('submit',SubmitType::class,['attr'=>['class'=>'w-full btn-info my-0.5'],
            'label'=>'Valider'
            ])
            ->addEventListener(FormEvents::POST_SUBMIT,$this->addDate(...))
        ;
    }

    public function addDate(PostSubmitEvent $event)
    {
        $data = $event->getData();
        if(!($data instanceof Civility)) return;
        $data->setCreatedAt(new \DateTimeImmutable());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Civility::class,
        ]);
    }
}
