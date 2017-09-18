<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Documentable;

/**
 * Documentable trait.
 *
 * Should be used inside entity where you need to store documents
 */
trait DocumentableMethods
{

    /**
     * Set documentFolder
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\DocumentFolder $documentFolder
     *
     * @return $this
     */
    public function setDocumentFolder(\Siciarek\SymfonyCommonBundle\Entity\DocumentFolder $documentFolder = null)
    {
        $this->documentFolder = $documentFolder;

        return $this;
    }

    /**
     * Get documentFolder
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\DocumentFolder
     */
    public function getDocumentFolder()
    {
        return $this->documentFolder;
    }
}