<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use App\Entity\Usuarios;
use App\Repository\UsuariosRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pacientes')]
class PacientesController extends AbstractController
{
    //**********************************************************
    // Modificar Perfil de Facultativos a traves de Formulario *
    //**********************************************************
    #[Route('/modificarfacultativo', name: 'modificarFacultativo', methods: ['GET', 'POST'])]
    public function modificarFacultativo(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variables de sesion de usuario y paciente
        $idusuario = $request->getSession()->get('idusuario');
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idusuario);
        dump($idfacultativo);

        // Recupero datos de usuario para enviar los Values a Formulario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);
        dump($usuariomodificar);
        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativomodificar = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativomodificar);

        $facultativo = new Facultativo();
        $formularioPerfilFacultativo = $this->createForm(
            PerfilFacultativoType::class,
            $facultativo
        );

        $formularioPerfilFacultativo->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioPerfilFacultativo->isSubmitted() &&
            $formularioPerfilFacultativo->isValid()
        ) {
            dump($formularioPerfilFacultativo);
            // Recogemos los campos del Formulario en Array para tratarlos
            $dataformulario = $formularioPerfilFacultativo->getData();
            dump($dataformulario);
            //$email = $request->request->get('email');
            $email = $request->query->get('email');
            $nombre = $request->query->get('nombre');
            $apellido1 = $request->query->get('apellido1');
            $apellido2 = $request->query->get('apellido2');
            $telefono = $request->query->get('telefono');
            $especialidad = $request->query->get('especialidad');

            // Modifico el Email con el recibido en formulario
            $usuariomodificar->setEmail($email);
            dump($usuariomodificar);

            // Modificamos los valores con los datos del Formulario, el ID no se puede modificar es clave
            // $pacientemodificar->setIdpaciente($idpaciente);
            $facultativomodificar->setNombre($nombre);
            $facultativomodificar->setApellido1($apellido1);
            $facultativomodificar->setApellido2($apellido2);
            $facultativomodificar->setTelefono($telefono);
            $facultativomodificar->setEspecialidad($especialidad);
            // Guardo el usuario antes de guardar Paciente con el objeto usuario
            $facultativomodificar->setIdusuario($usuariomodificar);
            dump($facultativomodificar);

            // Modificamos el Usuario
            $em->persist($usuariomodificar);
            $em->flush();

            // Modificamos el Facultativo
            $em->persist($facultativomodificar);
            $em->flush();

            // Construimos mensaje de modificacion correcta
            $mensaje =
                'Se ha modificado los Datos del Facultativo ' . $idfacultativo;

            // Devuelvo control a Pagina Inicio de Facultativo mandando mensaje
            return $this->render('dashboard/dashboardFacultativo.html.twig', [
                'mensaje' => $mensaje,
            ]);
        }

        // Envio a la vista de Datos Perfil Facultativo mandando el formulario
        return $this->render('facultativo/modificarPerfil.html.twig', [
            'perfilFacultativoForm' => $formularioPerfilFacultativo->createView(),
        ]);
    }
}
