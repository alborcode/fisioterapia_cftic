<?php

namespace App\Controller;

use App\Entity\Turnos;
use App\Form\TurnosType;
use App\Repository\TurnosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/turnos')]
class TurnosController extends AbstractController
{
    #[Route('/', name: 'app_turnos_index', methods: ['GET'])]
    public function index(TurnosRepository $turnosRepository): Response
    {
        return $this->render('turnos/index.html.twig', [
            'turnos' => $turnosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_turnos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, TurnosRepository $turnosRepository): Response
    {
        $turno = new Turnos();
        $form = $this->createForm(TurnosType::class, $turno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $turnosRepository->add($turno, true);

            return $this->redirectToRoute('app_turnos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('turnos/new.html.twig', [
            'turno' => $turno,
            'form' => $form,
        ]);
    }

    #[Route('/{idturno}', name: 'app_turnos_show', methods: ['GET'])]
    public function show(Turnos $turno): Response
    {
        return $this->render('turnos/show.html.twig', [
            'turno' => $turno,
        ]);
    }

    #[Route('/{idturno}/edit', name: 'app_turnos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Turnos $turno, TurnosRepository $turnosRepository): Response
    {
        $form = $this->createForm(TurnosType::class, $turno);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $turnosRepository->add($turno, true);

            return $this->redirectToRoute('app_turnos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('turnos/edit.html.twig', [
            'turno' => $turno,
            'form' => $form,
        ]);
    }

    #[Route('/{idturno}', name: 'app_turnos_delete', methods: ['POST'])]
    public function delete(Request $request, Turnos $turno, TurnosRepository $turnosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$turno->getIdturno(), $request->request->get('_token'))) {
            $turnosRepository->remove($turno, true);
        }

        return $this->redirectToRoute('app_turnos_index', [], Response::HTTP_SEE_OTHER);
    }
}
