<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Siciarek\SymfonyCommonBundle\Entity\ContactListEntry;
use Siciarek\SymfonyCommonBundle\Services\Utils\FilterInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as C;


class ContactListEntryType extends AbstractType
{
    const TYPE_FILTER_MAP = [
        ContactListEntry::TYPE_PHONE_NUMBER => FilterInterface::PHONE_NUMBER,
        ContactListEntry::TYPE_EMAIL_ADDRESS => FilterInterface::EMAIL_ADDRESS,
        ContactListEntry::TYPE_FACEBOOK_IDENTIFIER => FilterInterface::FACEBOOK_IDENTIFIER,
    ];

    const TYPE_ERROR_MAP = [
        ContactListEntry::TYPE_PHONE_NUMBER => 'This value is not a valid phone number.',
        ContactListEntry::TYPE_EMAIL_ADDRESS => 'This value is not a valid email address.',
        ContactListEntry::TYPE_FACEBOOK_IDENTIFIER => 'This value is not a valid facebook identifier.',
    ];

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * ContactListEntryType constructor.
     */
    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

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
            ->add('value', null, [
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
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {

                /**
                 * @var ContactListEntry $obj
                 */
                $obj = $event->getForm()->getData();

                $type = $obj->getType();
                $value = $obj->getValue();

                if ($value !== null) {
                    $value = $this->filter->sanitize($value, [self::TYPE_FILTER_MAP[$type]], true);

                    if ($value === null) {
                        $event->getForm()->get('value')->addError(new FormError(self::TYPE_ERROR_MAP[$type]));
                    }

                    $obj->setValue($value);
                }

                $event->setData($obj);
            });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ContactListEntry::class,
        ]);
    }
}