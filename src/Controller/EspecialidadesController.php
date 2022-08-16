<?php

namespace App\Controller;

use App\Entity\Especialidades;
use App\Form\Especialidades1Type;
use App\Repository\EspecialidadesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/especialidades')]
class EspecialidadesController extends AbstractController
{
    #[Route('/', name: 'app_especialidades_index', methods: ['GET'])]
    public function index(EspecialidadesRepository $especialidadesRepository): Response
    {
        return $this->render('especialidades/index.html.twig', [
            'especialidades' => $especialidadesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_especialidades_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EspecialidadesRepository $especialidadesRepository): Response
    {
        $especialidade = new Especialidades();
        $form = $this->createForm(Especialidades1Type::class, $especialidade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $especialidadesRepository->add($especialidade, true);

            return $this->redirectToRoute('app_especialidades_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('especialidades/new.html.twig', [
            'especialidade' => $especialidade,
            'form' => $form,
        ]);
    }

    #[Route('/{especialidad}', name: 'app_especialidades_show', methods: ['GET'])]
    public function show(Especialidades $especialidade): Response
    {
        return $this->render('especialidades/show.html.twig', [
            'especialidade' => $especialidade,
        ]);
    }

    #[Route('/{especialidad}/edit', name: 'app_especialidades_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Especialidades $especialidade, EspecialidadesRepository $especialidadesRepository): Response
    {
        $form = $this->createForm(Especialidades1Type::class, $especialidade);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $especialidadesRepository->add($especialidade, true);

            return $this->redirectToRoute('app_especialidades_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('especialidades/edit.html.twig', [
            'especialidade' => $especialidade,
            'form' => $form,
        ]);
    }

    #[Route('/{especialidad}', name: 'app_especialidades_delete', methods: ['POST'])]
    public function delete(Request $request, Especialidades $especialidade, EspecialidadesRepository $especialidadesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$especialidade->getEspecialidad(), $request->request->get('_token'))) {
            $especialidadesRepository->remove($especialidade, true);
        }

        return $this->redirectToRoute('app_especialidades_index', [], Response::HTTP_SEE_OTHER);
    }
}
