Jak używać funkcji QueryBuildera
--------------------------------

Poniżej użycie funkcji query buildera ``select``, ``update``, ``delete``.

Dla wszystkich przypadków:

.. code-block:: php

    /**
     * @var EntityManagerInterface $entityManager
     */
    $entityManager; // '@doctrine.orm.entity_manager'

select()
========

.. code-block:: php

    $result = $entityManager
        ->getRepository(\AppBundle\Entity\Document::class)
        ->createQueryBuilder('doc')
        ->select()
        ->where('doc.id = :id')
        ->setParameters([
            'id' => 4,
        ])
        ->getQuery()
        ->execute();

update()
========

.. code-block:: php

    $result = $entityManager
        ->createQueryBuilder()
        ->update(\AppBundle\Entity\Document::class, 'doc')
        ->set('doc.title', ':title')
        ->set('doc.main', ':main')
        ->set('doc.enabled', ':enabled')
        ->where('doc.id = :id')
        ->setParameters([
            'id' => 4,
            'title' => 'Default document',
            'main' => true,
            'enabled' => true,
        ])
        ->getQuery()
        ->execute();

delete()
========

.. code-block:: php

    $result = $entityManager
        ->createQueryBuilder()
        ->delete(\AppBundle\Entity\Document::class, 'doc')
        ->where('doc.id = :id')
        ->setParameters([
            'id' => 3,
        ])
        ->getQuery()
        ->execute();


