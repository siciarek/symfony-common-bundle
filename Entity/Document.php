<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Siciarek\SymfonyCommonBundle\Model as DBBehaviors;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Siciarek\SymfonyCommonBundle\Entity\Document
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_document")
 * @ORM\Entity(repositoryClass="DocumentRepository")
 */
class Document
{
    const DEFAULT_MIME_TYPE = 'application/octet-stream';
    const TYPE_FILE_DOCUMENT = 'file_document';
    const TYPE_FILE_IMAGE = 'file_image';
    const TYPE_FILE_MEDIA = 'file_media';
    const TYPE_REFERENCE_DOCUMENT = 'reference_document';
    const TYPE_REFERENCE_IMAGE = 'reference_image';
    const TYPE_REFERENCE_MEDIA = 'reference_media';

    const AVAILABLE_TYPES = [
        self::TYPE_FILE_DOCUMENT,
        self::TYPE_FILE_IMAGE,
        self::TYPE_FILE_MEDIA,
        self::TYPE_REFERENCE_DOCUMENT,
        self::TYPE_REFERENCE_IMAGE,
        self::TYPE_REFERENCE_MEDIA,
    ];

    use DBBehaviors\Descriptable\Descriptable,
        ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable;

    public function __toString()
    {
        return (string) $this->title;
    }

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Siciarek\SymfonyCommonBundle\Entity\DocumentFolder", inversedBy="documents")
     */
    private $folder;

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled = true;

    /**
     * @ORM\Column(type="boolean")
     */
    private $main = false;

    /**
     * @ORM\Column()
     */
    private $type = self::TYPE_FILE_DOCUMENT;

    /**
     * @ORM\Column()
     */
    private $mimeType = self::DEFAULT_MIME_TYPE;

    /**
     * @ORM\Column()
     */
    private $title;

    /**
     * @ORM\Column(type="integer")
     */
    private $size = 0;

    /**
     * @ORM\Column()
     * @Assert\File()
     */
    private $file;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return Document
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * Set main
     *
     * @param boolean $main
     *
     * @return Document
     */
    public function setMain($main)
    {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain()
    {
        return $this->main;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Document
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set mimeType
     *
     * @param string $mimeType
     *
     * @return Document
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * Get mimeType
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Document
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set size
     *
     * @param integer $size
     *
     * @return Document
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return integer
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Document
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set folder
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\DocumentFolder $folder
     *
     * @return Document
     */
    public function setFolder(\Siciarek\SymfonyCommonBundle\Entity\DocumentFolder $folder = null)
    {
        $this->folder = $folder;

        return $this;
    }

    /**
     * Get folder
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\DocumentFolder
     */
    public function getFolder()
    {
        return $this->folder;
    }
}
