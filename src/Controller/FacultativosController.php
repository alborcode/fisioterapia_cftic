<?php

namespace App\Controller;

use App\Entity\Facultativos;
use App\Form\FacultativosType;
use App\Repository\FacultativosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facultativos')]
class FacultativosController extends AbstractController
{
    #[Route('/', name: 'app_facultativos_index', methods: ['GET'])]
    public function index(FacultativosRepository $facultativosRepository): Response
    {
        return $this->render('facultativos/index.html.twig', [
            'facultativos' => $facultativosRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_facultativos_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FacultativosRepository $facultativosRepository): Response
    {
        $facultativo = new Facultativos();
        $form = $this->createForm(FacultativosType::class, $facultativo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facultativosRepository->add($facultativo, true);

            return $this->redirectToRoute('app_facultativos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facultativos/new.html.twig', [
            'facultativo' => $facultativo,
            'form' => $form,
        ]);
    }

    #[Route('/{idfacultativo}', name: 'app_facultativos_show', methods: ['GET'])]
    public function show(Facultativos $facultativo): Response
    {
        return $this->render('facultativos/show.html.twig', [
            'facultativo' => $facultativo,
        ]);
    }

    #[Route('/{idfacultativo}/edit', name: 'app_facultativos_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Facultativos $facultativo, FacultativosRepository $facultativosRepository): Response
    {
        $form = $this->createForm(FacultativosType::class, $facultativo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $facultativosRepository->add($facultativo, true);

            return $this->redirectToRoute('app_facultativos_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('facultativos/edit.html.twig', [
            'facultativo' => $facultativo,
            'form' => $form,
        ]);
    }

    #[Route('/{idfacultativo}', name: 'app_facultativos_delete', methods: ['POST'])]
    public function delete(Request $request, Facultativos $facultativo, FacultativosRepository $facultativosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$facultativo->getIdfacultativo(), $request->request->get('_token'))) {
            $facultativosRepository->remove($facultativo, true);
        }

        return $this->redirectToRoute('app_facultativos_index', [], Response::HTTP_SEE_OTHER);
    }
}
