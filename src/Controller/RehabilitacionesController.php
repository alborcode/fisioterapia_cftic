<?php

namespace App\Controller;

use App\Entity\Rehabilitaciones;
use App\Form\RehabilitacionesType;
use App\Repository\RehabilitacionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rehabilitaciones')]
class RehabilitacionesController extends AbstractController
{
    #[Route('/', name: 'app_rehabilitaciones_index', methods: ['GET'])]
    public function index(RehabilitacionesRepository $rehabilitacionesRepository): Response
    {
        return $this->render('rehabilitaciones/index.html.twig', [
            'rehabilitaciones' => $rehabilitacionesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rehabilitaciones_new', methods: ['GET', 'POST'])]
    public function new(Request $request, RehabilitacionesRepository $rehabilitacionesRepository): Response
    {
        $rehabilitacione = new Rehabilitaciones();
        $form = $this->createForm(RehabilitacionesType::class, $rehabilitacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rehabilitacionesRepository->add($rehabilitacione, true);

            return $this->redirectToRoute('app_rehabilitaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rehabilitaciones/new.html.twig', [
            'rehabilitacione' => $rehabilitacione,
            'form' => $form,
        ]);
    }

    #[Route('/{idrehabilitacion}', name: 'app_rehabilitaciones_show', methods: ['GET'])]
    public function show(Rehabilitaciones $rehabilitacione): Response
    {
        return $this->render('rehabilitaciones/show.html.twig', [
            'rehabilitacione' => $rehabilitacione,
        ]);
    }

    #[Route('/{idrehabilitacion}/edit', name: 'app_rehabilitaciones_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Rehabilitaciones $rehabilitacione, RehabilitacionesRepository $rehabilitacionesRepository): Response
    {
        $form = $this->createForm(RehabilitacionesType::class, $rehabilitacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $rehabilitacionesRepository->add($rehabilitacione, true);

            return $this->redirectToRoute('app_rehabilitaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('rehabilitaciones/edit.html.twig', [
            'rehabilitacione' => $rehabilitacione,
            'form' => $form,
        ]);
    }

    #[Route('/{idrehabilitacion}', name: 'app_rehabilitaciones_delete', methods: ['POST'])]
    public function delete(Request $request, Rehabilitaciones $rehabilitacione, RehabilitacionesRepository $rehabilitacionesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$rehabilitacione->getIdrehabilitacion(), $request->request->get('_token'))) {
            $rehabilitacionesRepository->remove($rehabilitacione, true);
        }

        return $this->redirectToRoute('app_rehabilitaciones_index', [], Response::HTTP_SEE_OTHER);
    }
}
