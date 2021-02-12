<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category", name="category")
     */
    public function index(): Response
    {
        return $this->render('category/index.html.twig', [
            'controller_name' => 'CategoryController',
        ]);
    }

/**
*@route("/admin/category/create" , name="category_create")
*/
    public function create(CategoryRepository $categoryRepository, Request $request){

    


    return $this->render('category/create.html.twig');
    }

/**
*@route("/admin/category/{id}/edit" , name="category_edit")
*/
    public function edit($id, CategoryRepository $categoryRepository, Request $request){
    
        $category = $categoryRepository->find($id);
    


    return $this->render('category/edit.html.twig',[

        'category'=>$category
    ]);
    }
}
