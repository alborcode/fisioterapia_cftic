<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pacientes')]
class PacientesController extends AbstractController
{
    #[Route('/', name: 'app_pacientes_index', methods: ['GET'])]
    public function index(PacientesRepository $pacientesRepository): Response
    {
        return $this->render('pacientes/index.html.twig', [
            'pacientes' => $pacientesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_pacientes_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PacientesRepository $pacientesRepository): Response
    {
        $paciente = new Pacientes();
        $form = $this->createForm(PacientesType::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pacientesRepository->add($paciente, true);

            return $this->redirectToRoute('app_pacientes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pacientes/new.html.twig', [
            'paciente' => $paciente,
            'form' => $form,
        ]);
    }

    #[Route('/{idpaciente}', name: 'app_pacientes_show', methods: ['GET'])]
    public function show(Pacientes $paciente): Response
    {
        return $this->render('pacientes/show.html.twig', [
            'paciente' => $paciente,
        ]);
    }

    #[Route('/{idpaciente}/edit', name: 'app_pacientes_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Pacientes $paciente, PacientesRepository $pacientesRepository): Response
    {
        $form = $this->createForm(PacientesType::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $pacientesRepository->add($paciente, true);

            return $this->redirectToRoute('app_pacientes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('pacientes/edit.html.twig', [
            'paciente' => $paciente,
            'form' => $form,
        ]);
    }

    #[Route('/{idpaciente}', name: 'app_pacientes_delete', methods: ['POST'])]
    public function delete(Request $request, Pacientes $paciente, PacientesRepository $pacientesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$paciente->getIdpaciente(), $request->request->get('_token'))) {
            $pacientesRepository->remove($paciente, true);
        }

        return $this->redirectToRoute('app_pacientes_index', [], Response::HTTP_SEE_OTHER);
    }
}
