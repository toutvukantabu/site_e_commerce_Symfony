<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
    
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
    }
}
