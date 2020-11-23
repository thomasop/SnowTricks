<?php

namespace App\Tool;

use Doctrine\ORM\EntityManagerInterface;

class Remove
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function removeEntity(Object $entity)
    {
        $this->entityManager->Remove($entity);
        $this->entityManager->flush();
    }
}
