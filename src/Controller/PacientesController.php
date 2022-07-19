<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Pacientes;
use Doctrine\ORM\EntityManagerInterface;

class PacientesController extends AbstractController
{

    // Formulario de Conexion
    #[Route('/loginpaciente', name: 'loginpaciente')]
    public function loginpaciente()
    { 
        return $this->render('paciente.html.twig');
    }

    // Registrar nuevo Paciente
    #[Route('/newpaciente', name: 'altaPaciente')]
    public function altaPaciente(Request $request, EntityManagerInterface $em)
    { 
        // Recupero los datos de paciente del formulario
        $nombre = $request->request->get('txtNombre');
        $apellido1 = $request->request->get('txtApellido1');
        $apellido2 = $request->request->get('txtApellido2');
        $direccion = $request->request->get('txtDireccion');
        $poblacion = $request->request->get('txtpoblacion');
        $cp = $request->request->get('txtCP');
        $provincia = $request->request->get('txtProvincia');
        $telefono = $request->request->get('txtTelefono');

        // Nueva instancia de Paciente      
        $paciente = new Pacientes();
        $paciente->setNombre($nombre);
        $paciente->setApellido1($apellido1);
        $paciente->setApellido2($apellido2);
        $paciente->setDireccion($direccion);
        $paciente->setPoblacion($poblacion);
        $paciente->setProvincia($provincia);
        $paciente->setTelefono($telefono);
                
        $em->persist($paciente);
        $em->flush();
      
        // Establece mensaje Flash
        $this->addFlash(
            'notice',
            'Paciente creado correctamente'
        );
        // Redirigimos conexion a Pagina Inicial de Pacientes
        return $this->redirectToRoute("pacienteIni.html.twig");
            
    }
}