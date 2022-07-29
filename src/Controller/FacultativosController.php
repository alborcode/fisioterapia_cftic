<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Facultativos;
use Doctrine\ORM\EntityManagerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

// Para control de acceso en controladores, Se utiliza anotacion a nivel de clase para proteger las acciones del controlador
// /** * @Security("is_granted('ROLE_FACULTATIVO'), statusCode=404, message="No tiene acceso a este recurso.") */    
class FacultativosController extends AbstractController
{

    // Formulario de Conexion
    #[Route('/loginfacultativo', name: 'loginfacultativo')]
    public function loginfacultativo()
    { 
        return $this->render('facultativo.html.twig');
    }

    // Registrar nuevo Facultativo
    #[Route('/newfacultativo', name: 'altaFacultativo')]
    public function altaFacultativo(Request $request, EntityManagerInterface $em)
    { 
        // Recupero los datos de facultativo del formulario
        $especialidad = $request->request->get('txtEspecialidad');
        $nombre = $request->request->get('txtNombre');
        $apellido1 = $request->request->get('txtApellido1');
        $apellido2 = $request->request->get('txtApellido2');
        $telefono = $request->request->get('txtTelefono');

        // Nueva instancia de Paciente      
        $facultativo = new Facultativos();
        $facultativo->setEspecialidad($especialidad);
        $facultativo->setNombre($nombre);
        $facultativo->setApellido1($apellido1);
        $facultativo->setApellido2($apellido2);
        $facultativo->setTelefono($telefono);
                
        $em->persist($facultativo);
        $em->flush();
      
        // Establece mensaje Flash
        $this->addFlash(
            'notice',
            'Facultativo creado correctamente'
        );
        // Redirigimos conexion a Pagina Inicial de Facultativos
        return $this->redirectToRoute("facultativoIni.html.twig");
            
    }
}