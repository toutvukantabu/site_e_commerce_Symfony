<?php 

namespace App\Doctrine\Listener;

use App\Entity\Category;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategorySlugListener{
    
    public function __construct(protected \Symfony\Component\String\Slugger\SluggerInterface $slugger)
    {
    }

    public function prePersist(Category $entity,LifecycleEventArgs $event)
    {
        $entity = $event->getObject();
     
        if(!$entity instanceof Category){
            return;
        }
        if($entity->getSlug() === null || $entity->getSlug() === '' || $entity->getSlug() === '0'){

            $entity->setSlug(strtolower($this->slugger->slug($entity->getName())));

        }

    }
}