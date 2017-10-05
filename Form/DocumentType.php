<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Knp\DoctrineBehaviors\ORM\Geocodable\Type\Point;
use Siciarek\SymfonyCommonBundle\Entity\Address;
use Siciarek\SymfonyCommonBundle\Entity\ContactListEntry;
use Siciarek\SymfonyCommonBundle\Entity\Document;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as C;


class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, [
                'trim' => true,
                'constraints' => [
                    new C\Length(['min' => 1, 'max' => 255]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'trim' => true,
                'constraints' => [
                    new C\Length(['min' => 1, 'max' => 255]),
                ],
            ])
            ->add('file', FileType::class, [
                'data_class' => null,
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}