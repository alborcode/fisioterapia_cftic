<?php
namespace App\Controller;

use App\Entity\Aseguradoras;
use App\Repository\AseguradorasRepository;
use App\Form\AseguradorasType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/especialidades')]
class EspecialidadesController extends AbstractController
{
    //*******************************
    // Alta de Datos de Aseguradora *
    //*******************************
    #[Route('/altaespecialidad', name: 'altaEspecialidad', methods: ['GET', 'POST'])]
    public function altaEspecialidad(
        Request $request,
        EspecialidadesRepository $especialidadesRepository,
        EntityManagerInterface $em
    ) {
        // Creo objeto Especialidad
        $especialidad = new Especialidades();
        $formularioEspecialidad = $this->createForm(
            EspecialidadesFormType::class,
            $especialidad
        );

        $formularioEspecialidad->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioEspecialidad->isSubmitted() &&
            $formularioEspecialidad->isValid()
        ) {
            dump($formularioEspecialidad);

            // Damos de Alta la Aseguradora en Base de Datos
            $em->persist($especialidad);
            $em->flush();

            //Recupero el identificador de Especialidad para el mensaje
            $idespecialidad = $especialidad->getIdespecialidad();
            dump($idespecialidad);
            $nombreespecialidad = $especialidad->getespecialidad();
            dump($nombreespecialidad);

            // Construimos mensaje de alta correcta
            $mensaje =
                'Se ha dado de alta Especialidad ' .
                $nombreespecialidad .
                ' con codigo ' .
                $idespecialidad;

            // Devuelvo control a Pagina Inicio de Administrativo mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Envio al Formulario de Especialidad mandando el formulario
        return $this->render('especialidades/altaEspecialidad.html.twig', [
            'perfilEspecialidadForm' => $formularioEspecialidad->createView(),
        ]);
    }

    //**********************************
    // Modificar Datos de Especialidad *
    //**********************************
    // Mostrar las Aseguradoras existentes
    #[Route('/buscaraespecialidamodificar', name: 'buscarEspecialidaModificar', methods: ['GET', 'POST'])]
    public function buscarEspecialidaModificar(
        Request $request,
        EspecialidadesRepository $especialidadesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos las Especialidades
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        return $this->render('especialidades/mostrarEspecialidades.html.twig', [
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Mostrar Datos de Especialidades segun busqueda introducida por Nombre
    #[Route('/buscarespecialidadnombre', name: 'buscarEspecialidadNombreModificar', methods: ['GET', 'POST'])]
    public function buscarEspecialidadNombreModificar(
        Request $request,
        EspecialidadesRepository $especialidadesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datobusqueda = $request->request->get('txtNombre');
        dump($datobusqueda);

        $query = $em->createQuery(
            'SELECT e FROM App\Entity\Especialidades e WHERE e.especialidad like :parametro'
        );
        // Concateno la variable a buscar y el % del Like
        $query->setParameter('parametro', $datobusqueda . '%');
        dump($query);

        $especialidades = $query->getResult();
        dump($datos);

        return $this->render('especialidades/mostrarEspecialidades.html.twig', [
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Modificar Datos de Especialidades
    #[Route('/modificarespecialidad', name: 'modificarEspecialidad', methods: ['GET', 'POST'])]
    public function modificarEspecialidad(
        Request $request,
        EspecialidadesRepository $especialidadesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Identificador de Especialida que me llega con Get
        $idespecialidad = $request->query->get('idespecialidad');
        dump($idespecialidad);

        // Recupero datos de informe para enviar los Values a Formulario modificacion
        $especialidadmodificar = $em
            ->getRepository(Especialidades::class)
            ->findOneByIdinforme($idespecialidad);
        dump($especialidadmodificar);

        // Creo objeto Especialidad
        $especialidad = new Especialidades();
        $formularioEspecialidad = $this->createForm(
            EspecialidadesFormType::class,
            $especialidad
        );

        $formularioEspecialidad->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioEspecialidad->isSubmitted() &&
            $formularioEspecialidad->isValid()
        ) {
            dump($formularioEspecialidad);

            // Modificamos la Aseguradora en Base de Datos
            $em->persist($especialidad);
            $em->flush();

            //Recupero nombre de Aseguradora modificado para el mensaje
            $nombreespecialidad = $especialidad->getEspecialidad();
            dump($nombreespecialidad);

            // Construimos mensaje de modificacion correcta
            $mensaje =
                'Se ha modificado la especialidad ' .
                $nombreespecialidad .
                ' con codigo ' .
                $idespecialidad;

            // Devuelvo control a Pagina Inicio de Administrativo mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Envio al Formulario de Aseguradoras mandando el formulario
        return $this->render('especialidad/altaEspecialidad.html.twig', [
            'perfilEspecialidadForm' => $formularioEspecialidad->createView(),
        ]);
    }
}
