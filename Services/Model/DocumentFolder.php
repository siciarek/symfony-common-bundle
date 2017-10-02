<?php
/**
 * Created by PhpStorm.
 * User: siciarek
 * Date: 19.09.17
 * Time: 18:58
 */

namespace Siciarek\SymfonyCommonBundle\Services\Model;

use Doctrine\ORM\EntityManagerInterface;
use Siciarek\SymfonyCommonBundle\Entity as E;

class DocumentFolder
{

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * ContactList constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Adds document to owner's document folder.
     *
     * @return bool returns if operation succeed
     */
    public function add()
    {

        # Return success:
        return true;
    }
}
