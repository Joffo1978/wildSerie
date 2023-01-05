<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

#[Route('/program')]

class ProgramController extends AbstractController

{

    #[Route('/', name:'program_index')]

    public function index(RequestStack $requestStack) : Response

    {

        $session = $requestStack->getSession();

        // some code using $session

    }

    #[Route('/new', name: 'app_program_new')]
    public function new(Request $request, ProgramRepository $programRepository): Response

    {

        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
//var_dump($request);
//die();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $programRepository->save($program, true);


            // Redirect to programR list

            return $this->redirectToRoute('app_program_index');

        }


        // Render the form

        return $this->renderForm('program/new.html.twig', [

            'form' => $form,

        ]);

    }
}