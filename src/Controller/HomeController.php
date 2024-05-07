<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route(path: '/', name: 'homepage')]
    public function homepage(ProductRepository $productRepository): \Symfony\Component\HttpFoundation\Response
    {
        $products = $productRepository->findBy([], [], 3);

        return $this->render('home.html.twig', [
            'products' => $products,
        ]);
    }
}
