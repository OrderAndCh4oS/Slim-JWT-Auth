<?php
/**
 * User: Sean Cooper
 * Date: 23/07/2016
 * Time: 20:32
 */

namespace Oacc\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Oacc\Entity\User;
use Oacc\Utility\PasswordEncoder;

/**
 * Class HashPasswordListener
 * @package Oacc\Authentication
 */
class HashPasswordListener implements EventSubscriber
{
    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
    }

    /**
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if (!$entity instanceof User) {
            return;
        }
        $this->encodePassword($entity);
        $entityManager = $args->getEntityManager();
        $meta = $entityManager->getClassMetadata(get_class($entity));
        $entityManager->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param $entity
     */
    public function encodePassword(User $entity)
    {
        $encoded = PasswordEncoder::encodePassword($entity->getPlainPassword());
        $entity->setPassword($encoded);
        $entity->eraseCredentials();
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
}
