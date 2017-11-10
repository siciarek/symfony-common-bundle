<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Siciarek\SymfonyCommonBundle\Entity\Parameter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ParameterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('value', CheckboxType::class, [
                'error_bubbling' => true,
                'required' => false,
                'label' => false,
                'empty_data' => false,
            ]);

        $builder->get('value')->addModelTransformer(new CallbackTransformer(
            function ($original) {
                return json_decode($original);
            },
            function ($submitted) {
                return json_encode($submitted);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = [
            'data_class' => Parameter::class,
            'csrf_protection' => false,
        ];

        $resolver->setDefaults($defaults);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}