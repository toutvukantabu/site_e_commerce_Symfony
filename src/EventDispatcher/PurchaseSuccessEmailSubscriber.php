<?php

namespace App\EventDispatcher;


use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;


class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    public function __construct(protected \Psr\Log\LoggerInterface $logger, protected \Symfony\Component\Mailer\MailerInterface $mailer)
    {
    }

    public static function getSubscribedEvents()
    {

        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent): void
    {
        $purchaseSuccessEvent->getPurchase()->getUser();
        $purchase = $purchaseSuccessEvent->getPurchase();
        $email = new TemplatedEmail();
        $email->to(new Address($purchaseSuccessEvent->getPurchase()->getUser()->getEmail(),$purchaseSuccessEvent->getPurchase()->getUser()->getFullName()))
        ->from("contact@mail.com")
        ->subject("Bravo,votre commande n°({$purchase->getId()}) à bien été confirmée")
        ->htmlTemplate('emails/purchase_success.html.twig')
        ->context([
            'purchase' => $purchase,
            'user'=> $purchaseSuccessEvent->getPurchase()->getUser()
        ]);
        $this->mailer->send($email);
        $this->logger->info("Email envoyé pour la commande n°" . $purchaseSuccessEvent->getPurchase()->getId());
    }
}
