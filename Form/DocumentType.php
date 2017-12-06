<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Siciarek\SymfonyCommonBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as C;


class DocumentType extends AbstractType
{
    /**
     * @var array
     */
    private $config;

    /**
     * DocumentType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $targetDirectory = $container->hasParameter('document_target_directory')
            ? $container->getParameter('document_target_directory')
            : $container->get('kernel')->getProjectDir() . '/web/uploads';

        $this->config = [
            'target_directory' => $targetDirectory,
        ];
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'trim' => true,
                'choices' => array_combine(Document::AVAILABLE_TYPES, Document::AVAILABLE_TYPES),
                'constraints' => [
                    new C\NotBlank(),
                    new C\Choice(['choices' => Document::AVAILABLE_TYPES]),
                ],
            ])
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
                    new C\Length(['min' => 5, 'max' => 255]),
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                /**
                 * @var Document $obj
                 * @var UploadedFile $file
                 */
                $obj = $event->getForm()->getData();

                if ($obj->getType() === Document::TYPE_FILE) {
                    $file = $obj->getFile();

                    if (false === $file instanceof UploadedFile) {
                        $constraint = new C\File();
                        $event->getForm()->get('file')->addError(new FormError($constraint->disallowEmptyMessage));
                    } else {

                        $obj->setSize($file->getSize());
                        $obj->setMimeType($file->getMimeType());
                        $obj->setExtension($file->getClientOriginalExtension());

                        if ($obj->getTitle() === null) {
                            $obj->setTitle($file->getClientOriginalName());
                        }

                        $directory = implode(DIRECTORY_SEPARATOR, [
                            $options['target_directory'],
                            rand(10, 99),
                            rand(1, 9),
                        ]);
                        $name = md5(microtime()).'.'.$file->getClientOriginalExtension();

                        $obj->setFile($file->move($directory, $name));
                        $obj->setReference(null);
                    }
                } elseif ($obj->getType() === Document::TYPE_REFERENCE) {
                    $obj->setFile(null);
                    $obj->setSize(null);
                    $obj->setExtension(null);
                }

                $event->setData($obj);
            });

        $adjustForm = function (FormInterface $form, $type) {

            switch ($type) {
                case Document::TYPE_FILE:
                    $form
                        ->add('file', null, [
                            'required' => true,
                            'data_class' => null,
                            'constraints' => [
                                new C\File(),
                            ],
                        ]);
                    break;

                case Document::TYPE_REFERENCE:
                    $form
                        ->add('reference', null, [
                            'required' => true,
                            'trim' => true,
                            'constraints' => [
                                new C\Length(['min' => 5, 'max' => 255]),
                            ],
                        ]);
                    break;
            }

        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($adjustForm) {
                $form = $event->getForm();
                $type = $event->getData()->getType();

                $adjustForm($form, $type);
            }
        );

        $builder->get('type')->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($adjustForm) {
                $form = $event->getForm()->getParent();
                $type = $event->getData();

                $adjustForm($form, $type);
            }
        );
    }

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     * @throws \Exception
     */
    public function configureOptions(
        OptionsResolver $resolver
    ) {
        if (false === (isset($this->config['target_directory'])
                and is_dir($this->config['target_directory'])
                and is_writable($this->config['target_directory']))) {
            throw new \Exception('Invalid document target directory.');
        }

        $defaults = array_merge(['data_class' => Document::class], $this->config);

        $resolver->setDefaults($defaults);
    }

    /**
     * Get block prefix.
     *
     * @return null|string
     */
    public function getBlockPrefix()
    {
        return null;
    }
}