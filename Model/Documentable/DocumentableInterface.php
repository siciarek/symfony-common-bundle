<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Documentable;

/**
 * Documentable interface.
 *
 * Should be implemented by entity where you need to add addresses to entities
 */
interface DocumentableInterface
{
    /**
     * Set documentFolder
     *
     * @param \Siciarek\SymfonyCommonBundle\Entity\DocumentFolder $documentFolder
     *
     * @return $this
     */
    public function setDocumentFolder(\Siciarek\SymfonyCommonBundle\Entity\DocumentFolder $documentFolder = null);

    /**
     * Get documentFolder
     *
     * @return \Siciarek\SymfonyCommonBundle\Entity\DocumentFolder
     */
    public function getDocumentFolder();
}