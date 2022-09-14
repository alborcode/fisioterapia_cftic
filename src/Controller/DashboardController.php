<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pacientes;
use App\Entity\Facultativos;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'app_dashboard')]
    public function app_dashboard(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        // Deniega el acceso en caso de que no este validado
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Se recupera el id del usuario y rol de UserInterface para poder acceder a Pacientes y Facultativos
        $rol = $this->getUser()->getRoles()[0];
        //$idusuario = $this->getUser()->getIdUsuario();
        $idusuario = $this->getUser()->getUserIdentifier();

        // Recupero Identificador de sesion (Token) del usuario de la peticion
        $session = $request->getSession();
        // Guardo Usuario en Session
        $session->set('idusuario', $idusuario);
        // Guardo Rol en Session
        $session->set('rol', $rol);

        // Controla a que pagina redirigir segun el Rol del Usuario conectado
        if ($rol == 'ROLE_PACIENTE') {
            // Si es paciente se valida si ya se ha dado de alta buscando por usuario
            $paciente = $em
                ->getRepository(Pacientes::class)
                ->findOneByIdusuario($idusuario);

            // Si no existe datos es que no hay un paciente dado de alta para ese usuario
            if (!$paciente) {
                // Se envia a plantilla de Alta de Paciente mandando el codigo usuario
                return $this->redirectToRoute('insertarPaciente');
                // Si no ya existe paciente y se carga pagina de inicio de Pacientes
            } else {
                $idpaciente = $paciente->getIdpaciente();
                // Guardo Paciente en Session
                $session->set('idpaciente', $idpaciente);
                $paginainicio = 'dashboard/dashboardPaciente.html.twig';
            }
        }

        if ($rol == 'ROLE_FACULTATIVO') {
            // Si es facultativo se recupera el identificador a partir del idusuario
            $facultativo = $em
                ->getRepository(Facultativos::class)
                ->findOneByIdusuario($idusuario);

            $idfacultativo = $facultativo->getIdfacultativo();
            // Guardo Facultativo en Session
            $session->set('idfacultativo', $idfacultativo);
            //Se carga pagina de inicio de Facultativos
            $paginainicio = 'dashboard/dashboardFacultativo.html.twig';
        }

        if ($rol == 'ROLE_ADMINISTRATIVO') {
            // Si es administrativo se habrá dado de alta manualmente
            $paginainicio = 'dashboard/dashboardAdministrativo.html.twig';
            dump($paginainicio);
        }

        // Devuelve pagina a la que ir tras login
        return $this->render($paginainicio);
    }
}
