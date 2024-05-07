<?php

namespace App\EventDispatcher;

use App\Event\ProductViewEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class productViewSuccessSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger, protected MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'productView.Success' => 'sendSuccesProductView',
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

        $this->logger->info('vous venez de voir le produit n°');
    }
}
