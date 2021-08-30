<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id":"\d+"})
     */
    public function add($id, ProductRepository $productRepository, SessionInterface $session)
    {

        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException("le produit $id n'éxiste pas");
        } else {

            $cart = $session->get('cart', []);
            if (array_key_exists($id, $cart)) {

                $cart[$id]++;
            } else {

                $cart[$id] = 1;
            }
            $session->set('cart', $cart);

        $this->addFlash('success', "le produit a bien été ajouté au panier");

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(){

        return $this->render('cart/index.html.twig');
    }
}
