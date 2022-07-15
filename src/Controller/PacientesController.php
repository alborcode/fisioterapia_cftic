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
        return $this->render('pacienteIni.html.twig');
    }

    // Registrar nuevo Paciente
    #[Route('/newpaciente', name: 'altaPaciente')]
    public function altaPaciente(Request $request, EntityManagerInterface $em, int $idusuario)
    { 
        // Recupero el idusuario del campo oculto del formulario de Pacientes
        $idusuario  = $request->request->get('txtIdusuario');
        
        // Recupero los datos de paciente del formulario
        $nombre     = $request->request->get('txtNombre');
        $apellido1  = $request->request->get('txtApellido1');
        $apellido2  = $request->request->get('txtApellido2');
        $direccion  = $request->request->get('txtDireccion');
        $poblacion  = $request->request->get('txtpoblacion');
        $cp         = $request->request->get('txtCp');
        $provincia  = $request->request->get('txtProvincia');
        $telefono   = $request->request->get('txtTelefono');

        // Nueva instancia de Paciente      
        $paciente = new Pacientes();
        $paciente->setIdusuario($idusuario); 
        $paciente->setNombre($nombre);
        $paciente->setApellido1($apellido1);
        $paciente->setApellido2($apellido2);
        $paciente->setDireccion($direccion);
        $paciente->setPoblacion($poblacion);
        $paciente->setCp($cp);
        $paciente->setProvincia($provincia);
        $paciente->setTelefono($telefono);
                
        $em->persist($paciente);
        $em->flush();
      
        $mensaje = 'Paciente dado de Alta';
        // Redirigimos conexion a Pagina Inicial de Pacientes tras alta de Paciente
        return $this->render('pacienteIni.html.twig', 
        [
            ['mensaje' => $mensaje]
        ]);
            
    }
}