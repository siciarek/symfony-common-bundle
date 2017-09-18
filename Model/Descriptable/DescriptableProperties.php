<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\Descriptable;

use Doctrine\ORM\Mapping as ORM;


/**
 * Descriptable trait.
 *
 * Should be used inside entity where you need to store extra description (short) and info (longer)
 */
trait DescriptableProperties
{
    /**
     * @ORM\Column(nullable=true)
     * @var string
     */
    protected $description;

    /**
     * @ORM\Column(length=1024, nullable=true)
     * @var string
     */
    protected $info;
}