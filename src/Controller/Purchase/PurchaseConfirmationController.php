<?php

namespace App\Controller\Purchase;


use App\Entity\Purchase;
use App\Cart\CartService;
use App\Purchase\PurchasePersister;
use App\Form\CartConfirmationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class PurchaseConfirmationController extends AbstractController
{


    protected $cartService;
    protected $em;
    protected $persister;

    public function __construct(CartService $cartService, EntityManagerInterface $em, PurchasePersister $persister)
    {
        $this->cartService = $cartService;
        $this->em = $em;
        $this->persister = $persister;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message ="Vous devez être connecté pour effectuer une commande")
     */
    public function confirm(Request $request)
    {
        $form = $this->createForm(CartConfirmationType::class);

        $form->handleRequest($request);
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', 'vous devez remplir le formulaire de confirmation');
            return $this->redirectToRoute('cart_show');
        }

        $user = $this->getUser();

        $cartItems = $this->cartService->getDetailCartItem();

        if (count($cartItems) === 0) {

            $this->addFlash('warning', 'Vous ne pouvez pas valider une commande avec un panier vide');

            return $this->redirectToRoute('cart_show');
        }
        /** @var Purchase  */
        $purchase = $form->getData();

        $this->persister->storePurchase($purchase);

        return $this->redirectToRoute('purchase_payement_form', [
            'id' => $purchase->getId()

        ]);
    }
}
