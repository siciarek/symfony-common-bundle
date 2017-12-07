<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\NameDescInfo;

use Doctrine\ORM\Mapping as ORM;

/**
 * NameDescInfo trait.
 *
 * Should be used inside entity where you need to describe entity with name, description and info.
 */
trait NameDescInfoProperties
{
    /**
     * @ORM\Column()
     */
    private $name;

    /**
     * @ORM\Column(nullable=true, length=512)
     */
    private $description;

    /**
     * @ORM\Column(nullable=true, type="text")
     */
    private $info;
}