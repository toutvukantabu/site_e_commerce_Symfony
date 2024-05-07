<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Form\CartConfirmationType;
use App\Purchase\PurchasePersister;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PurchaseConfirmationController extends AbstractController
{
    public function __construct(protected CartService $cartService, protected EntityManagerInterface $em, protected PurchasePersister $persister)
    {
    }

    #[\Symfony\Component\Security\Http\Attribute\IsGranted('ROLE_USER', message: 'Vous devez être connecté pour effectuer une commande')]
    #[Route(path: '/purchase/confirm', name: 'purchase_confirm')]
    public function confirm(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'vous devez remplir le formulaire de confirmation');

            return $this->redirectToRoute('cart_show');
        }

        $this->getUser();

        $cartItems = $this->cartService->getDetailCartItem();

        if ([] === $cartItems) {
            $this->addFlash('warning', 'Vous ne pouvez pas valider une commande avec un panier vide');

            return $this->redirectToRoute('cart_show');
        }
        /** @var Purchase */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        return $this->redirectToRoute('purchase_payement_form', [
            'id' => $purchase->getId(),
        ]);
    }
}
