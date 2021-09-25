<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class productViewSuccessSubscriber implements EventSubscriberInterface
{
    protected $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public static function getSubscribedEvents()
    {

        return [
            'productView.success' => 'sendSuccesproductView'
        ];
    }

    public function sendSuccesproductView(ProductViewEvent $productViewEvent)
    {
        $this->logger->info("vous venez de voir le produit n°" . $productViewEvent->GetProduct()->getId());
    }
}
