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
    public function altaFacultativo(
        Request $request,
        EntityManagerInterface $em,
        // Se añade Slugger para poder subir archivo
        SluggerInterface $slugger
    ) {
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
        $this->addFlash('notice', 'Facultativo creado correctamente');

        // Creamos objeto Entidad Facultativo ver si es necesario pasar valores nulos
        $facultativo = new Facultativos();
        // Creamos formulario relacionando la entidad. El formulario lo creamos con php bin/console make:form y se crea siempre acabado en Type
        $form_facultativo = $this->createForm(
            FacultativoType::class,
            $facultativo
        );
        // Añado solicitud Request
        $form_facultativo->handleRequest($request);
        if ($form_facultativo->isSubmitted() && $form_facultativo->isValid()) {
            $em = $this->getDoctrine()->getManager();
            // Recuperamos Archivo a subir del Formulario
            $archivo = $form_facultativo->gey('InformeGuardar')->getData();
            // Si hay archivo
            if ($archivo) {
                $originalFilename = pathinfo(
                    $archivo->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename =
                    $safeFilename .
                    '-' .
                    uniqid() .
                    '.' .
                    $archivo->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $archivo->move(
                        // Nombre de directorio donde se almacenara se cambia tambien en config services.yaml
                        $this->getParameter('informes_directorio'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Upps');
                }
                $informe->SetInformeGuardar($newFilename);
            }
            //Persistimos el Formulario a traves de la Entidad
            $em->persist($facultativo);
            $em->flush();
            return $this->redirectToRoute(loginfacultativo);
        }
        // Este formulario se le pasa a Twig
        return $this->render('facultativoIni.html.twig', [
            // Se pasa el formulario instanciando la vista del formulario con createview
            'formulario' => $form_facultativo->createView(),
        ]);

        // Redirigimos conexion a Pagina Inicial de Facultativos
        //return $this->redirectToRoute("facultativoIni.html.twig");
    }
}
