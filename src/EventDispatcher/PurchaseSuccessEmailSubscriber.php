<?php

namespace App\EventDispatcher;

use App\Event\PurchaseSuccessEvent;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(protected LoggerInterface $logger, protected MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {
        return [
            'purchase.success' => 'sendSuccessEmail',
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent): void
    {
        $purchase = $purchaseSuccessEvent->getPurchase();
        $email = new TemplatedEmail();
        $email->to(new Address($purchaseSuccessEvent->getPurchase()->getUser()->getEmail(), $purchaseSuccessEvent->getPurchase()->getUser()->getFullName()))
        ->from('contact@mail.com')
        ->subject("Bravo,votre commande n°({$purchase->getId()}) à bien été confirmée")
        ->htmlTemplate('emails/purchase_success.html.twig')
        ->context([
            'purchase' => $purchase,
            'user' => $purchaseSuccessEvent->getPurchase()->getUser(),
        ]);
        $this->mailer->send($email);
        $this->logger->info('Email envoyé pour la commande n°'.$purchaseSuccessEvent->getPurchase()->getId());
    }
}
