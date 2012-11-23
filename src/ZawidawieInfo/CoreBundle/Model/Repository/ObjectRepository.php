<?php

namespace ZawidawieInfo\CoreBundle\Model\Repository;

use Doctrine\ORM\EntityRepository;

abstract class ObjectRepository extends EntityRepository
{

    public function getObjectManager()
    {
        return $this->getEntityManager();
    }

    public function getObjectClass()
    {
        return $this->getEntityName();
    }

    public function getObjectIdentifier()
    {
        return reset($this->getClassMetadata()->identifier);
    }

}
