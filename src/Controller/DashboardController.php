<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function index(): Response
    {
        // return $this->render('dashboard/index.html.twig', [
        //     'controller_name' => 'DashboardController',
        // ]);

        // Deniega el acceso en caso de que no este validado
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Controla a que pagina redirigir segun el Rol del Usuario conectado
        if ('ROLE_PACIENTE' === $this->getUser()->getRole()) {
            return $this->render('dashboard/dashboardPaciente.html.twig', [
                'controller_name' => 'DashboardController',
            ]);
        }
        if ('ROLE_FACULTATIVO' === $this->getUser()->getRole()) {
            return $this->render('dashboard/dashboardFacultativo.html.twig', [
                'controller_name' => 'DashboardController',
            ]);
        }
        if ('ROLE_ADMINISTRATIVO' === $this->getUser()->getRole()) {
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'controller_name' => 'DashboardController',
                ]
            );
        }
    }
}
