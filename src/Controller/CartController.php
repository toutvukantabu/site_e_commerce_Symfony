<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;;



class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, CartService $cartService)
    {

        $product = $productRepository->find($id);
        if (!$product) {

            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $cartService->add($id);

        $this->addFlash('success', "le produit a bien été ajouté au panier");

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(CartService $cartService)
    {
        $detailedcart = $cartService->getDetailCartItem();
        $total = $cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'items' => $detailedcart
        ]);
    }

    /**
     * @Route("/cart/deletet/{id}", name="cart_delete", requirements = {"id" = "\d+"})
     */
    public function delete($id, ProductRepository $productRepository, CartService $cartService)
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id demandé n'éxiste pas et ne peut être supprimé !");
        }
        $cartService->remove($id);

        $this->addFlash('success', "le produit a bien été supprimé du panier");

        return $this->redirectToRoute("cart_show");
    }
}
