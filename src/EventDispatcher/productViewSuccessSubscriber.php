<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mime\Address;

class productViewSuccessSubscriber implements EventSubscriberInterface
{
    protected $logger;
    protected $mailer;

    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public static function getSubscribedEvents()
    {

        return [
            'productView.Success' => 'sendSuccesProductView'
        ];
    }

    public function sendSuccesProductView(ProductViewEvent $productViewEvent)
    {
        // $email = new Email();
        // $email->from(new Address("contact@mail.com", "info de la boutique"))
        //     ->to("admin@mail.com")
        //     ->text("un visiteur est en train de voir le produit n°" . $productViewEvent->GetProduct()->getId())
        //     ->subject("visite du produit n°" . $productViewEvent->GetProduct()->getId());
        // $this->mailer->send($email);

        $this->logger->info("vous venez de voir le produit n°");
    }
}
