<?php

namespace App\Controller;

use App\Entity\Vacaciones;
use App\Form\VacacionesType;
use App\Repository\VacacionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/vacaciones')]
class VacacionesController extends AbstractController
{
    #[Route('/', name: 'app_vacaciones_index', methods: ['GET'])]
    public function index(VacacionesRepository $vacacionesRepository): Response
    {
        return $this->render('vacaciones/index.html.twig', [
            'vacaciones' => $vacacionesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_vacaciones_new', methods: ['GET', 'POST'])]
    public function new(Request $request, VacacionesRepository $vacacionesRepository): Response
    {
        $vacacione = new Vacaciones();
        $form = $this->createForm(VacacionesType::class, $vacacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacacionesRepository->add($vacacione, true);

            return $this->redirectToRoute('app_vacaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vacaciones/new.html.twig', [
            'vacacione' => $vacacione,
            'form' => $form,
        ]);
    }

    #[Route('/{idvacaciones}', name: 'app_vacaciones_show', methods: ['GET'])]
    public function show(Vacaciones $vacacione): Response
    {
        return $this->render('vacaciones/show.html.twig', [
            'vacacione' => $vacacione,
        ]);
    }

    #[Route('/{idvacaciones}/edit', name: 'app_vacaciones_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Vacaciones $vacacione, VacacionesRepository $vacacionesRepository): Response
    {
        $form = $this->createForm(VacacionesType::class, $vacacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $vacacionesRepository->add($vacacione, true);

            return $this->redirectToRoute('app_vacaciones_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('vacaciones/edit.html.twig', [
            'vacacione' => $vacacione,
            'form' => $form,
        ]);
    }

    #[Route('/{idvacaciones}', name: 'app_vacaciones_delete', methods: ['POST'])]
    public function delete(Request $request, Vacaciones $vacacione, VacacionesRepository $vacacionesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$vacacione->getIdvacaciones(), $request->request->get('_token'))) {
            $vacacionesRepository->remove($vacacione, true);
        }

        return $this->redirectToRoute('app_vacaciones_index', [], Response::HTTP_SEE_OTHER);
    }
}
