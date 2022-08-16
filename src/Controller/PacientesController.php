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
    #[Route('/alta', name: 'insertarPaciente', methods: ['GET', 'POST'])]
    public function insertarPaciente(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Usuario que me llega con Get (por seguridad se guarda en usuarios de sesion no por Get)
        //$usuario = $request->query->get('usuario');
        //$rol = $request->query->get('rol');
        // Recupero las variables de sesion
        $idusuario = $request->getSession()->get('idusuario');
        $rol = $request->getSession()->get('rol');
        // Imprimo las variables de Sesion usuario y rol
        dump('$idusuario:' . $idusuario);
        dump('$rol:' . $rol);

        $paciente = new Pacientes();
        $formularioPaciente = $this->createForm(
            PacientesType::class,
            $paciente
        );

        $formularioPaciente->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioPaciente->isSubmitted() &&
            $formularioPaciente->isValid()
        ) {
            dump($paciente);
            dump($formularioPaciente);
            // Recupero los datos del Formulario
            // $nombre = $request->request->get('txtNombre');
            // $apellido1 = $request->request->get('txtApellido1');
            // $apellido2 = $request->request->get('txtApellido2');
            // $telefono = $request->request->get('txtTelefono');
            // $direccion = $request->request->get('txtDireccion');
            // $codigopostal = $request->request->get('txtCodigopostal');
            // $poblacion = $request->request->get('txtpoblacion');
            // $provincia = $request->request->get('txtprovincia');
            // $paciente = new Hospital();
            // $paciente->setNombre($nombre);
            // $paciente->SetApellido1($apellido1);
            // $paciente->SetApellido2($apellido2);
            // $paciente->setTelefono($telefono);
            // $paciente->setDireccion($direccion);
            // $paciente->setCodigopostal($codigopostal);
            // $paciente->setPoblacion($poblacion);
            // $paciente->setProvincia($provincia);

            // Se accede al objeto usuario para guardarlo en Tabla Pacientes
            $usuario = $em->getRepository(Usuarios::class)->find($idusuario);
            dump($usuario);
            // Guardo el usuario antes de guardar Paciente con el objeto usuario
            $paciente->setIdusuario($usuario);
            dump($paciente);
            // La provincia viene como Array por lo que la actualizo
            //$provincia = $this->$paciente->getProvincia()[0];
            //$idusuario = $this->getUser()->getIdusuario();
            //dump($provincia);
            //$paciente->setIdusuario($usuario);

            $em->persist($paciente);
            $em->flush();
            //$pacientesRepository->add($paciente, true);
            dump($paciente);

            // Recupero el Id del Paciente guardado
            $idpaciente = $paciente->getIdpaciente();

            dump('$idpaciente:' . $idpaciente);
            // Recupero Identificador de sesion (Token) del usuario de la peticion
            $session = $request->getSession();
            // Guardo el Id del Paciente en Sesion
            $session->set('idpaciente', $idpaciente);
            dump('idusuario:' . $idusuario);
            dump('rol:' . $rol);
            dump('idpaciente:' . $idpaciente);

            $mensaje =
                'Se ha dado de alta el Paciente con codigo ' . $idpaciente;

            // Devuelvo control a Pagina Inicio de Paciente mandando mensaje
            return $this->render('dashboard/dashboardPaciente.html.twig', [
                'mensaje' => $mensaje,
            ]);
        }

        // Envio a la vista mandando el formulario
        return $this->render('pacientes/altaPaciente.html.twig', [
            'pacienteForm' => $formularioPaciente->createView(),
        ]);
    }
}
