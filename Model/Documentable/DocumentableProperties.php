<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Documentable;

use Doctrine\ORM\Mapping as ORM;

/**
 * Documentable trait.
 *
 * Should be used inside entity where you need to store documents
 */
trait DocumentableProperties
{
    /**
     * @ORM\OneToOne(targetEntity="Siciarek\SymfonyCommonBundle\Entity\DocumentFolder")
     */
    private $documentFolder;
}