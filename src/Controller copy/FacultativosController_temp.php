<?php

namespace App\Controller;

use App\Entity\Facultativos;
use App\Form\FacultativosType;
use App\Repository\FacultativosRepository;
use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facultativos')]
class FacultativosController extends AbstractController
{
    #[Route('/alta', name: 'insertarFacultativo', methods: ['GET', 'POST'])]
    public function insertarFacultativo(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variables de sesion
        $idusuario = $request->getSession()->get('idusuario');
        $rol = $request->getSession()->get('rol');
        // Imprimo las variables de Sesion usuario y rol
        dump('$idusuario:' . $idusuario);
        dump('$rol:' . $rol);

        $facultativo = new Facultativos();
        $formularioFacultativo = $this->createForm(
            FacultativosType::class,
            $facultativo
        );

        $formularioFacultativo->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioFacultativo->isSubmitted() &&
            $formularioFacultativo->isValid()
        ) {
            // Se accede al objeto usuario para guardarlo en Tabla Pacientes
            $usuario = $em->getRepository(Usuarios::class)->find($idusuario);
            dump($usuario);
            // Guardo el usuario antes de guardar Paciente con el objeto usuario
            $facultativo->setIdusuario($usuario);
            dump($facultativo);

            $em->persist($facultativo);
            $em->flush();

            dump($facultativo);
            // Recupero el Id del Facultativo guardado
            $idfacultativo = $facultativo->getIdfacultativo();

            dump('$idfacultativo:' . $idfacultativo);
            // Recupero Identificador de sesion (Token) del usuario de la peticion
            $session = $request->getSession();
            // Guardo el Id del Facultativo en Sesion
            $session->set('idfacultativo', $idfacultativo);
            dump('idusuario:' . $idusuario);
            dump('rol:' . $rol);
            dump('idfacultativo:' . $idfacultativo);

            $mensaje =
                'Se ha dado de alta el Facultativo con codigo ' .
                $idfacultativo;

            // Devuelvo control a Pagina Inicio de Facultativo mandando mensaje
            return $this->render('dashboard/dashboardFacultativo.html.twig', [
                'mensaje' => $mensaje,
            ]);
        }

        // Envio a la vista mandando el formulario
        return $this->render('pacientes/altaFacultativo.html.twig', [
            'facultativoForm' => $formularioFacultativo->createView(),
        ]);
    }
}
