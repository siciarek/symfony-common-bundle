<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 18.09.17
 * Time: 13:59
 */

namespace Siciarek\SymfonyCommonBundle\Model\NameDescInfo;

/**
 * NameDescInfo trait.
 *
 * Should be implemented when you need to describe entity with name, description and info.
 */
interface NameDescInfoInterface
{
    /**
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name);

    /**
     * Get name
     *
     * @return string
     */
    public function getName();

    /**
     * Set description
     *
     * @param string $description
     *
     * @return $this
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set info
     *
     * @param string $info
     *
     * @return $this
     */
    public function setInfo($info);

    /**
     * Get info
     *
     * @return string
     */
    public function getInfo();
}