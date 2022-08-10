<?php

namespace App\Controller;

use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/pacientes')]
class PacientesController extends AbstractController
{
    #[Route('/', name: 'altaPaciente', methods: ['GET'])]
    public function index(PacientesRepository $pacientesRepository): Response
    {
        return $this->render('pacientes/altaPaciente.html.twig', [
            'pacientes' => $pacientesRepository->findAll(),
        ]);
    }

    #[Route('/alta', name: 'insertarPaciente', methods: ['GET', 'POST'])]
    public function insertarPaciente(
        Request $request,
        EntityManagerInterface $em
    ) {
        // Recupero los datos del Formulario
        // $nombre = $request->request->get('txtNombre');
        // $apellido1 = $request->request->get('txtApellido1');
        // $apellido2 = $request->request->get('txtApellido2');
        // $telefono = $request->request->get('txtTelefono');
        // $direccion = $request->request->get('txtDireccion');
        // $codigopostal = $request->request->get('txtCodigopostal');
        // $poblacion = $request->request->get('txtpoblacion');
        // $poblacion = $request->request->get('txtprovincia');

        // $paciente = new Hospital();
        // $paciente->setNombre($nombre);
        // $paciente->setDireccion($direccion);
        // $paciente->setTelefono($telefono);
        // $paciente->setNumCamas($numcamas);
        // $em->persist($hospital);
        // $em->flush();

        $paciente = new Pacientes();
        $formularioPaciente = $this->createForm(
            PacientesType::class,
            $paciente
        );
        $formularioPaciente->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if ($formularioPaciente->isSubmitted() && $form->isValid()) {
            // Recupero el usuario que se paso a Vista
            $usuario = $request->request->get('usuario');
            // Guardo el usuario antes de guardar Paciente
            $paciente->setIdusuario($usuario);

            $entityManager->persist($paciente);
            $entityManager->flush();

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        // Envio a la vista mandando el formulario
        return $this->render('pacientes/altaPaciente.html.twig', [
            'formularioPaciente' => $formularioPaciente->createView(),
        ]);
    }
}
