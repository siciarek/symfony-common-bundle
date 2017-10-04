<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 19.09.17
 * Time: 18:58
 */

namespace Siciarek\SymfonyCommonBundle\Services\Model;

use Doctrine\ORM\EntityManagerInterface;
use Siciarek\SymfonyCommonBundle\Model\Documentable\DocumentableInterface;
use Siciarek\SymfonyCommonBundle\Services\Model\Exceptions;
use Siciarek\SymfonyCommonBundle\Entity as E;
use Siciarek\SymfonyCommonBundle\Services\Utils\FilterInterface;

class DocumentFolder
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * ContactList constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, FilterInterface $filter)
    {
        $this->entityManager = $entityManager;
        $this->filter = $filter;
    }

    /**
     * Adds document to owner's document folder.
     *
     * @param DocumentableInterface $owner
     * @param string $pathToFile path to file
     * @param null|string $title document title
     * @param null $description
     * @return bool returns true if operation succeed false otherwise
     * @throws Exceptions\Document
     */
    public function add(DocumentableInterface $owner, $pathToFile, $title = null, $description = null)
    {
        # File path validation:
        $pathToFile = $this->filter->sanitize($pathToFile, [FilterInterface::TRIM, FilterInterface::NULL]);
        if(false === file_exists($pathToFile)) {
            throw new Exceptions\Document('Invalid path: ' . $pathToFile);
        }

        # Title normalisation and complementation:
        $title = $this->filter->sanitize($title, [FilterInterface::TRIM, FilterInterface::NULL]);
        if($title === null) {
            $temp = explode(DIRECTORY_SEPARATOR, $pathToFile);
            $title = array_pop($temp);
        }

        # If owner has no document folder yet, set it up:
        if (false === $owner->getDocumentFolder() instanceof E\DocumentFolder) {
            $owner->setDocumentFolder(new E\DocumentFolder());
            $this->entityManager->persist($owner);
            $this->entityManager->flush();
        }

        $finfo = new \finfo(FILEINFO_MIME);
        $mimeType = $finfo->file($pathToFile);
        $size = filesize($pathToFile);

        $document = new E\Document();
        $document->setFolder($owner->getDocumentFolder());
        $document->setTitle($title);
        $document->setFile($pathToFile);
        $document->setSize($size);
        $document->setMimeType($mimeType);
        $document->setDescription($description);

        $this->entityManager->persist($document);
        $this->entityManager->flush();

        # Return success:
        return true;
    }
}
