<?php

namespace Siciarek\SymfonyCommonBundle\Form;

use Siciarek\SymfonyCommonBundle\Entity\Document;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as C;


class DocumentType extends AbstractType
{
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
            ->add('file', null, [
                'data_class' => null,
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
                    new C\Length(['min' => 1, 'max' => 255]),
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) use ($options) {
                /**
                 * @var Document $obj
                 * @var UploadedFile $file
                 */
                $obj = $event->getForm()->getData();
                $file = $obj->getFile();

                $obj->setSize($file->getSize());
                $obj->setMimeType($file->getMimeType());
                $obj->setExtension($file->getClientOriginalExtension());

                if($obj->getTitle() === null) {
                    $obj->setTitle($file->getClientOriginalName());
                }

                $directory = implode(DIRECTORY_SEPARATOR, [$options['target_directory'], rand(10, 99), rand(1, 9),]);
                $name = md5(microtime()).'.'.$file->getClientOriginalExtension();

                $obj->setFile($file->move($directory, $name));

                $event->setData($obj);
            });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
            'target_directory' => '/home/siciarek/Workspace/symfony-common-bundle/app/data',
        ]);
    }
}