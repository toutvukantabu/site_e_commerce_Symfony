<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    public function __construct(protected ProductRepository $productRepository, protected CartService $cartService)
    {
    }

    #[Route(path: '/cart/add/{id}', name: 'cart_add', requirements: ['id' => '\d+'])]
    public function add(int $id, Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $this->cartService->add($id);

        $this->addFlash('success', 'le produit a bien été ajouté au panier');
        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }
        if ($request->query->get('returnToHome')) {
            return $this->redirectToRoute('homepage');
        }

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug(),
        ]);
    }

    #[Route(path: '/cart', name: 'cart_show')]
    public function show(): \Symfony\Component\HttpFoundation\Response
    {
        $form = $this->createForm(CartConfirmationType::class);
        $detailedcart = $this->cartService->getDetailCartItem();
        $total = $this->cartService->getTotal();

        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'items' => $detailedcart,
            'confirmationForm' => $form,
        ]);
    }

    #[Route(path: '/cart/deletet/{id}', name: 'cart_delete', requirements: ['id' => '\d+'])]
    public function delete(int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id demandé n'éxiste pas et ne peut être supprimé !");
        }
        $this->cartService->remove($id);

        $this->addFlash('success', 'le produit a bien été supprimé du panier');

        return $this->redirectToRoute('cart_show');
    }

    #[Route(path: '/cart/decrements/{id}', name: 'cart_decrement', requirements: ['id' => '\d+'])]
    public function decrement(int $id): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $this->cartService->decrement($id);

        $this->addFlash('success', 'le produit a bien été enlevé au panier');

        return $this->redirectToRoute('cart_show');
    }
}
