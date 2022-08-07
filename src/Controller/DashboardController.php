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

        $rol = $this->getUser()->getRoles()[0];
        $usuario = $this->getUser()->getIdusuario();
        dump($rol);
        dump($usuario);
        // Por defecto para nuevas altas la pagina de inicio es la de Paciente
        $paginainicio = 'dashboard/dashboardPaciente.html.twig';

        // Controla a que pagina redirigir segun el Rol del Usuario conectado
        if ($rol == 'ROLE_PACIENTE') {
            $paginainicio = 'dashboard/dashboardPaciente.html.twig';
            dump($paginainicio);
        }
        if ($rol == 'ROLE_FACULTATIVO') {
            $paginainicio = 'dashboard/dashboardFacultativo.html.twig';
            dump($paginainicio);
        }
        if ($rol == 'ROLE_ADMINISTRATIVO') {
            $paginainicio = 'dashboard/dashboardAdministrativo.html.twig';
            dump($paginainicio);
        }

        // Devuelve pagina a la que ir
        return $this->render($paginainicio, [
            'usuario' => $usuario,
        ]);
    }
}
