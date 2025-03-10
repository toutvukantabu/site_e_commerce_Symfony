<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class CategoryController extends AbstractController
{
    // protected $categoryRepository;
    // public function renderMenuList(){
    //   $categories = $this->categoryRepository->findAll();
    //  return $this->render('caytegory/_menu.html.twig', [
    //      'category'=> $categories
    //  ]);
    // }
    #[Route(path: '/category', name: 'category')]
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

    #[route(path: '/admin/category/create', name: 'category_create')]
    public function create(Request $request, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $category = new Category;
        $form = $this->createForm(CategoryType::class, $category);
        $form->handlerequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $product = $form->getData();
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();
        return $this->render('category/create.html.twig', [
            'formView' => $formView
        ]);
    }
    #[route(path: '/admin/category/{id}/edit', name: 'category_edit')]
    public function edit($id, CategoryRepository $categoryRepository, Request $request, EntityManagerInterface $em, SluggerInterface $slugger)
    { 
        
       $category = $categoryRepository->find($id);

             if (!$category) {

           throw new NotFoundHttpException("Cette catégorie n'existe pas");
             }

            // $this->denyAccessUnlessGranted('CAN_EDIT', $category, "Vous n'êtes pas le popriétaire de cette catégorie");
    
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->flush();
            return $this->redirectToRoute('homepage');
        }
        $formView = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
