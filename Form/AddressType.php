<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Siciarek\SymfonyCommonBundle\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;


class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array_combine(Address::AVAILABLE_TYPES, Address::AVAILABLE_TYPES);

        if (true === $options['showTypeField']) {
            $builder
                ->add('type', ChoiceType::class, [
                    'trim' => true,
                    'choices' => $types,
                ]);
        }

        $builder
            ->add('address', TextType::class, [
                'trim' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 3, 'max' => 255]),
                ],
            ])
            ->add('postalCode', TextType::class, [
                'trim' => true,
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 6, 'max' => 6]),
                    new Regex(['pattern' => '/^\d{2}\-\d{3}$/']),
                ],
            ])
            ->add('place', TextType::class, [
                'trim' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'trim' => true,
                'constraints' => [
                    new Length(['min' => 1, 'max' => 255]),
                ],
            ])
            ->add('data', LocationType::class, [
                'required' => false,
                'empty_data' => [
                    'zoom' => 5,
                    'center' => [
                        'lat' => 52.069167,
                        'lon' => 19.480556,
                    ],
                    'coords' => [],
                ],
            ]);

        $builder->get('data')->addModelTransformer(new CallbackTransformer(
            function ($original) {
                return json_encode($original, JSON_PRETTY_PRINT);
            },
            function ($submitted) {
                return json_decode($submitted);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
            'showTypeField' => false,
        ]);
    }
}