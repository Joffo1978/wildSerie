<?php

namespace App\Controller;

use App\Form\CategoryType;
use App\Repository\SeasonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;


#[Route('/category')]
    class CategoryController extends AbstractController
    {
    #[Route('/', name: 'app_category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('Category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }
        #[Route('/new', name: 'app_category_new')]
        public function new(Request $request, CategoryRepository $categoryRepository): Response

        {

            $category = new Category();

            $form = $this->createForm(CategoryType::class, $category);

            $form->handleRequest($request);


            if ($form->isSubmitted() && $form->isValid()) {

                $categoryRepository->save($category, true);


                // Redirect to categories list

                return $this->redirectToRoute('category_index');

            }


            // Render the form

            return $this->renderForm('Category/new.html.twig', [

                'form' => $form,

            ]);

        }
    }
