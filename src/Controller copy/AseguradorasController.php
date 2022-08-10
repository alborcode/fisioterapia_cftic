<?php

namespace App\Controller;

use App\Entity\Aseguradoras;
use App\Form\AseguradorasType;
use App\Repository\AseguradorasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/aseguradoras')]
class AseguradorasController extends AbstractController
{
    #[Route('/', name: 'app_aseguradoras_index', methods: ['GET'])]
    public function index(AseguradorasRepository $aseguradorasRepository): Response
    {
        return $this->render('aseguradoras/index.html.twig', [
            'aseguradoras' => $aseguradorasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_aseguradoras_new', methods: ['GET', 'POST'])]
    public function new(Request $request, AseguradorasRepository $aseguradorasRepository): Response
    {
        $aseguradora = new Aseguradoras();
        $form = $this->createForm(AseguradorasType::class, $aseguradora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aseguradorasRepository->add($aseguradora, true);

            return $this->redirectToRoute('app_aseguradoras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('aseguradoras/new.html.twig', [
            'aseguradora' => $aseguradora,
            'form' => $form,
        ]);
    }

    #[Route('/{idaseguradora}', name: 'app_aseguradoras_show', methods: ['GET'])]
    public function show(Aseguradoras $aseguradora): Response
    {
        return $this->render('aseguradoras/show.html.twig', [
            'aseguradora' => $aseguradora,
        ]);
    }

    #[Route('/{idaseguradora}/edit', name: 'app_aseguradoras_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Aseguradoras $aseguradora, AseguradorasRepository $aseguradorasRepository): Response
    {
        $form = $this->createForm(AseguradorasType::class, $aseguradora);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $aseguradorasRepository->add($aseguradora, true);

            return $this->redirectToRoute('app_aseguradoras_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('aseguradoras/edit.html.twig', [
            'aseguradora' => $aseguradora,
            'form' => $form,
        ]);
    }

    #[Route('/{idaseguradora}', name: 'app_aseguradoras_delete', methods: ['POST'])]
    public function delete(Request $request, Aseguradoras $aseguradora, AseguradorasRepository $aseguradorasRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aseguradora->getIdaseguradora(), $request->request->get('_token'))) {
            $aseguradorasRepository->remove($aseguradora, true);
        }

        return $this->redirectToRoute('app_aseguradoras_index', [], Response::HTTP_SEE_OTHER);
    }
}
