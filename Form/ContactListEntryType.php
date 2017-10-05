<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Knp\DoctrineBehaviors\ORM\Geocodable\Type\Point;
use Siciarek\SymfonyCommonBundle\Entity\Address;
use Siciarek\SymfonyCommonBundle\Entity\ContactListEntry;
use Symfony\Bridge\Doctrine\Tests\Fixtures\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Validator\Constraints as C;


class ContactListEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $types = array_combine(ContactListEntry::AVAILABLE_TYPES, ContactListEntry::AVAILABLE_TYPES);

        $builder
            ->add('type', ChoiceType::class, [
                'trim' => true,
                'choices' => $types,
                'constraints' => [
                    new C\NotBlank(),
                    new C\Choice(['choices' => ContactListEntry::AVAILABLE_TYPES]),
                ],
            ])
            ->add('value', TextType::class, [
                'trim' => true,
                'constraints' => [
                    new C\NotBlank(),
                    new C\Length(['min' => 5, 'max' => 255]),
                ],
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'trim' => true,
                'constraints' => [
                    new C\Length(['min' => 1, 'max' => 255]),
                ],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactListEntry::class,
        ]);
    }
}