<?php

namespace App\Controller;

use App\Entity\Program;
use App\Form\ProgramType;
use App\Repository\ProgramRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Service\ProgramDuration;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/program')]

class ProgramController extends AbstractController

{

    #[Route('/', name:'program_index')]

    public function index(RequestStack $requestStack) : Response

    {

        $session = $requestStack->getSession();
        if (!$session->has('total')) {

            $session->set('total', 0); // if total doesn’t exist in session, it is initialized.

        }


        $total = $session->get('total');// get actual value in session with ‘total' key.

        // ...
// Render the form

        return $this->renderForm('program/session.html.twig', [

            'total' => $total,
    ]);
    }

    #[Route('/new', name: 'app_program_new')]
    public function new(Request $request, ProgramRepository $programRepository, MailerInterface $mailer, SluggerInterface $slugger, ProgramDuration $programDuration): Response

    {

        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);
//var_dump($request);
//die();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugger->slug($program->getTitle());

            $program->setSlug($slug);

            $programRepository->save($program, true);

            $email = (new Email())

                ->from($this->getParameter('mailer_from'))

                ->to('your_email@example.com')

                ->subject('Une nouvelle série vient d\'être publiée !')

                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));



            $mailer->send($email);
            // Once the form is submitted, valid and the data inserted in database, you can define the success flash message

            $this->addFlash('success', 'The new program has been created');


            // Redirect to programR list

            //

        }


        // Render the form


        return $this->renderForm('program/new.html.twig', [

            'form' => $form,
            'programDuration' => $programDuration->calculate($program)
        ]);

    }
}