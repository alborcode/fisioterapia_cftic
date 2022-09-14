<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Entity\Facultativos;
use App\Repository\FacultativosRepository;
use App\Entity\Especialidades;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/facultativos')]
class FacultativosController extends AbstractController
{
    //**********************************************************
    // Modificar Perfil de Facultativos a traves de Formulario *
    //**********************************************************
    #[Route('/mostrarfacultativo', name: 'mostrarDatosFacultativo', methods: ['GET', 'POST'])]
    public function mostrarDatosFacultativo(
        Request $request,
        EntityManagerInterface $em
    ) {
        // Recupero las variables de sesion de usuario y facultativo
        $idusuario = $request->getSession()->get('idusuario');
        $idfacultativo = $request->getSession()->get('idfacultativo');

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativomodificar = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero datos de usuario para enviar los Values a Formulario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Datos Perfil Paciente
        return $this->render(
            'facultativos/modificarDatosFacultativo.html.twig',
            [
                'datosUsuario' => $usuariomodificar,
                'datosFacultativo' => $facultativomodificar,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Recogemos Datos Formulario para modificar Perfil y Datos de Pacientes
    #[Route('/modificarfacultativo', name: 'modificarDatosFacultativo', methods: ['GET', 'POST'])]
    public function modificarFacultativoAdmin(
        Request $request,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idusuario = $request->query->get('idusuario');
        $idfacultativo = $request->query->get('idfacultativo');

        // Recogemos datos de formulario con Post
        $email = $request->request->get('txtEmail');
        dump($email);
        $nombre = $request->request->get('txtNombre');
        dump($nombre);
        $apellido1 = $request->request->get('txtApellido1');
        dump($apellido1);
        $apellido2 = $request->request->get('txtApellido2');
        dump($apellido2);
        $telefono = $request->request->get('txtTelefono');
        dump($telefono);
        $idespecialidad = $request->request->get('comboEspecialidad');
        dump($idespecialidad);

        // Recupero datos de objeto Especialidad antes de guardar Facultativo
        $especialidad = $em
            ->getRepository(Especialidades::class)
            ->findOneByIdespecialidad($idespecialidad);

        // Recupero datos de objeto Usuario con el idusuario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);

        // Modifico el Email del usuario con el recibido en formulario
        $usuariomodificar->setEmail($email);

        // Recupero el registro a modificar
        $facultativomodificar = $em
            ->getRepository(Facultativos::class)
            ->find($idfacultativo);

        // Modificamos los valores de Facultativo con los datos del Formulario, el ID no se puede modificar es clave
        $facultativomodificar->setNombre($nombre);
        $facultativomodificar->setApellido1($apellido1);
        $facultativomodificar->setApellido2($apellido2);
        $facultativomodificar->setTelefono($telefono);
        $facultativomodificar->setEspecialidad($especialidad);
        // Guardo el usuario antes de guardar Paciente con el objeto usuario
        $facultativomodificar->setIdusuario($usuariomodificar);

        // Modificamos el Usuario
        $em->persist($usuariomodificar);
        $em->flush();

        // Modificamos el Paciente
        $em->persist($facultativomodificar);
        $em->flush();

        // Construimos mensaje de modificacion correcta
        $mensaje = 'Se han modificado sus Datos';

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardFacultativo.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }
}
