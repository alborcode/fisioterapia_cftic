<?php

namespace App\Controller;

use App\Entity\Citas;
use App\Form\CitasType;
use App\Repository\CitasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/citas')]
class CitasController extends AbstractController
{
    #[Route('/', name: 'app_citas_index', methods: ['GET'])]
    public function index(CitasRepository $citasRepository): Response
    {
        return $this->render('citas/index.html.twig', [
            'citas' => $citasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_citas_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CitasRepository $citasRepository): Response
    {
        $cita = new Citas();
        $form = $this->createForm(CitasType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $citasRepository->add($cita, true);

            return $this->redirectToRoute('app_citas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('citas/new.html.twig', [
            'cita' => $cita,
            'form' => $form,
        ]);
    }

    #[Route('/{idcita}', name: 'app_citas_show', methods: ['GET'])]
    public function show(Citas $cita): Response
    {
        return $this->render('citas/show.html.twig', [
            'cita' => $cita,
        ]);
    }

    #[Route('/{idcita}/edit', name: 'app_citas_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Citas $cita, CitasRepository $citasRepository): Response
    {
        $form = $this->createForm(CitasType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $citasRepository->add($cita, true);

            return $this->redirectToRoute('app_citas_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('citas/edit.html.twig', [
            'cita' => $cita,
            'form' => $form,
        ]);
    }

    #[Route('/{idcita}', name: 'app_citas_delete', methods: ['POST'])]
    public function delete(Request $request, Citas $cita, CitasRepository $citasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cita->getIdcita(), $request->request->get('_token'))) {
            $citasRepository->remove($cita, true);
        }

        return $this->redirectToRoute('app_citas_index', [], Response::HTTP_SEE_OTHER);
    }
}
