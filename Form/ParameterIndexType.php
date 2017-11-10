<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Siciarek\SymfonyCommonBundle\Entity\ParameterIndex;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ParameterIndexType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('parameters', CollectionType::class, [
                'error_bubbling' => true,
                'required' => false,
                'entry_type' => ParameterType::class,
                'label' => false,
            ])
//            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
//                dump($event);
//                exit;
//
//                $event->setData($data);
//            });
;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $defaults = [
            'data_class' => ParameterIndex::class,
            'csrf_protection' => false,
        ];

        $resolver->setDefaults($defaults);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}