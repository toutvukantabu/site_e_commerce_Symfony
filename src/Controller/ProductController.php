<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Event\ProductViewEvent;
use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{
    #[Route(path: '/{slug}', name: 'product_category', priority: -1)]
    public function category($slug, CategoryRepository $categoryRepository): Response
    {
        $category = $categoryRepository->findOneBy([

            'slug' => $slug
        ]);
        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas");
        }

        return $this->render('product/category.html.twig', [
            'slug' => $slug,
            'category' => $category

        ]);
    }

    #[Route(path: '/{category_slug}/{slug}', name: 'product_show', priority: -1)]
    public function show($slug,ProductRepository $productRepository,EventDispatcherInterface $dispatcher): \Symfony\Component\HttpFoundation\Response
    {
        $product = $productRepository->findOneBy([

            'slug' => $slug
        ]);
        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas");
        }
        $event= new ProductViewEvent($product);

        $dispatcher->dispatch( $event,'productView.Success');
        return $this->render('product/show.html.twig', [

            'product' => $product,
        ]);
    }

    #[Route(path: '/admin/product/create', name: 'product_create')]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);

        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));
            $em->persist($product);
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
        $formView = $form->createView();
        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }

    #[Route(path: '/admin/product/{id}/edit', name: 'product_edit')]
    public function edit($id, ProductRepository $productRepository, Request $request, EntityManagerInterface $em)
    {
        $product = $productRepository->find($id);

        $form = $this->createForm(ProductType::class, $product);

        // $form->setData($product);
        $form->handlerequest($request);
        $formView = $form->createView();

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug' => $product->getSlug()
            ]);
        }
        $formView = $form->createView();
        return $this->render('product/edit.html.twig', [
            'product' => $product,
            'formView' => $formView
        ]);
    }
}
