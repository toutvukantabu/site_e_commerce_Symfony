<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;;



class CartController extends AbstractController
{

    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var CartService
     */
    protected $cartservice;

    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService = $cartService;
    }
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, Request $request)
    {

        $product = $this->productRepository->find($id);
        if (!$product) {

            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $this->cartService->add($id);

        $this->addFlash('success', "le produit a bien été ajouté au panier");
        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }
        if ($request->query->get('returnToHome')) {
            return $this->redirectToRoute('homepage');
        }
        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug' => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show()
    {
        $form = $this->createForm(CartConfirmationType::class);
        $detailedcart = $this->cartService->getDetailCartItem();
        $total = $this->cartService->getTotal();
        return $this->render('cart/index.html.twig', [
            'total' => $total,
            'items' => $detailedcart,
            'confirmationForm'=>$form->createView()

        ]);
    }

    /**
     * @Route("/cart/deletet/{id}", name="cart_delete", requirements = {"id" = "\d+"})
     */
    public function delete($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("Le produit $id demandé n'éxiste pas et ne peut être supprimé !");
        }
        $this->cartService->remove($id);

        $this->addFlash('success', "le produit a bien été supprimé du panier");

        return $this->redirectToRoute("cart_show");
    }

    /**
     * @Route("/cart/decrements/{id}", name="cart_decrement", requirements={"id":"\d+"})
     */
    public function decrement($id)
    {

        $product = $this->productRepository->find($id);
        if (!$product) {

            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        }
        $this->cartService->decrement($id);

        $this->addFlash('success', "le produit a bien été enlevé au panier");
        return $this->redirectToRoute("cart_show");
    }
}
