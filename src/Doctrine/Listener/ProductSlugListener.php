<?php

namespace App\Doctrine\Listener;

use App\Entity\Product;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class ProductSlugListener
{
    public function __construct(protected SluggerInterface $slugger)
    {
    }

    public function prePersist(Product $entity, LifecycleEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$entity instanceof Product) {
            return;
        }
        if (null === $entity->getSlug() || '' === $entity->getSlug() || '0' === $entity->getSlug()) {
            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));
        }
    }
}
