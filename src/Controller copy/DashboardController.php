<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pacientes;
use App\Repository\PacientesRepository;
use App\Entity\Facultativos;
use App\Repository\FacultativosRepository;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function app_dashboard(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Deniega el acceso en caso de que no este validado
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $rol = $this->getUser()->getRoles()[0];
        $usuario = $this->getUser()->getIdusuario();
        dump($rol);
        dump($usuario);
        // Variables con valores por defecto
        $paginainicio = 'dashboard/dashboardPaciente.html.twig';
        $usuario = 0;
        $paciente = 0;
        $facultativo = 0;

        // Controla a que pagina redirigir segun el Rol del Usuario conectado
        if ($rol == 'ROLE_PACIENTE') {
            // Si es paciente se valida si ya se ha dado de alta buscando por usuario
            $datos = $em
                ->getRepository(Pacientes::class)
                ->findByIdusuario($usuario);
            // Si no existe datos es que no hay un paciente dado de alta para ese usuario
            if (!$datos) {
                // Se envia a plantilla de Alta de Paciente mandando el codigo usuario
                // return $this->render('pacientes/new.html.twig', [
                //     'usuario' => $usuario,
                // ]);
                return $this->render('pacientes/altaPaciente.html.twig');
                // Si no ya existe paciente y se carga pagina de inicio de Pacientes
            } else {
                $paciente = $this->getIdpaciente()->getIdpaciente();
                dump($paciente);
                $paginainicio = 'dashboard/dashboardPaciente.html.twig';
                dump($paginainicio);
            }
        }

        if ($rol == 'ROLE_FACULTATIVO') {
            // Si es facultativo se recupera el identificador a partir del idusuario
            $datos = $em
                ->getRepository(Facultativos::class)
                ->findByIdusuario($usuario);
            $facultativo = $this->getIdfacultativo()->getIdfacultativo();
            dump($facultativo);
            //Se carga pagina de inicio de Facultativos
            $paginainicio = 'dashboard/dashboardFacultativo.html.twig';
            dump($paginainicio);
        }

        if ($rol == 'ROLE_ADMINISTRATIVO') {
            // Si es administrativo se habrá dado de alta manualmente
            $paginainicio = 'dashboard/dashboardAdministrativo.html.twig';
            dump($paginainicio);
        }

        // Devuelve pagina a la que ir tras login
        return $this->render($paginainicio, [
            'usuario' => $usuario,
            'rol' => $rol,
            'paciente' => $paciente,
            'facultativo' => $facultativo,
        ]);
    }
}
