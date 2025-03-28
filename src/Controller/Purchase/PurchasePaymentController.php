<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentController extends AbstractController
{

    #[\Symfony\Component\Security\Http\Attribute\IsGranted('ROLE_USER')]
    #[Route(path: '/purchase/pay/{id}', name: 'purchase_payement_form')]
    public function showCardForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService)
    {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase || $purchase && $purchase->getUser() !== $this->getUser() || $purchase && $purchase->getStatus() === Purchase::STATUS_PAID) {

            return $this->redirectToRoute('cart_show');
        }
        $intent = $stripeService->getPaymentIntent($purchase);
        return $this->render('purchase/payment.html.twig', [

            'clientSecret' => $intent->client_secret,
            'purchase' => $purchase,
            'stripePublicKey'=> $stripeService->getPublicKey()

        ]);
    }
}
