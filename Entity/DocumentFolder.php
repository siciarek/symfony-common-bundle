<?php

namespace Siciarek\SymfonyCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;
use Siciarek\SymfonyCommonBundle\Model as DBBehaviors;

/**
 * Siciarek\SymfonyCommonBundle\Entity\DocumentFolder
 *
 * @ORM\Entity
 * @ORM\Table(name="scb_document_folder")
 * @ORM\Entity(repositoryClass="DocumentFolderRepository")
 */
class DocumentFolder
{
    use DBBehaviors\Descriptable\Descriptable,
        ORMBehaviors\Blameable\Blameable,
        ORMBehaviors\Timestampable\Timestampable;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="Siciarek\SymfonyCommonBundle\Entity\Document", mappedBy="folder")
     */
    private $documents;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Add document
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\Document $document
     *
     * @return DocumentFolder
     */
    public function addDocument(\Siciarek\SymfonyCommonBundle\Entity\Document $document)
    {
        $this->documents[] = $document;

        return $this;
    }

    /**
     * Remove document
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\Document $document
     */
    public function removeDocument(\Siciarek\SymfonyCommonBundle\Entity\Document $document)
    {
        $this->documents->removeElement($document);
    }

    /**
     * Get documents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDocuments()
    {
        return $this->documents;
    }
}
