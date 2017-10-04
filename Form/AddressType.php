<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Siciarek\SymfonyCommonBundle\Entity\Address;
use Symfony\Component\Form\AbstractType;
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

        $builder
            ->add('type', ChoiceType::class, [
                'choices' => $types,
            ])
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
                    new Regex(['pattern' => '/^\d{2}\-\d{3}$/']),
                ],
            ])
            ->add('place', TextType::class, [
                'trim' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('setUpLocation', CheckboxType::class, [
                'trim' => true,
                'data' => false,
                'required' => false,
                'mapped' => false,
            ])
            ->add('description', TextareaType::class, [
                'trim' => true,
                'constraints' => [
                    new Length(['min' => 1, 'max' => 255]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}