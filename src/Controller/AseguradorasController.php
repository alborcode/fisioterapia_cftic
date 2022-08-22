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

#[Route('/aseguradoras')]
class AseguradorasController extends AbstractController
{
    //*******************************
    // Alta de Datos de Aseguradora *
    //*******************************
    #[Route('/altaaseguradora', name: 'altaAseguradora', methods: ['GET', 'POST'])]
    public function altaAseguradora(
        Request $request,
        AseguradorasRepository $aseguradorasRepository,
        EntityManagerInterface $em
    ) {
        // Creo objeto Aseguradora
        $aseguradora = new Aseguradoras();
        $formularioAseguradora = $this->createForm(
            AseguradorasType::class,
            $aseguradora
        );

        $formularioAseguradora->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioAseguradora->isSubmitted() &&
            $formularioAseguradora->isValid()
        ) {
            dump($formularioAseguradora);

            // Damos de Alta la Aseguradora en Base de Datos
            $em->persist($aseguradora);
            $em->flush();

            //Recupero el identificador de Aseguradora para el mensaje
            $idaseguradora = $aseguradora->getIdaseguradora();
            dump($idaseguradora);

            // Construimos mensaje de alta correcta
            $mensaje =
                'Se ha dado de alta Aseguradora con codigo ' . $idaseguradora;

            // Devuelvo control a Pagina Inicio de Administrativo mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Envio al Formulario de Aseguradora mandando el formulario
        return $this->render('aseguradoras/altaAseguradora.html.twig', [
            'aseguradoraForm' => $formularioAseguradora->createView(),
        ]);
    }

    //*********************************
    // Modificar Datos de Aseguradora *
    //*********************************
    // Mostrar las Aseguradoras existentes
    #[Route('/buscaraseguradoramodificar', name: 'buscarAseguradoraModificar', methods: ['GET', 'POST'])]
    public function buscarpacienteMobuscarAseguradoraModificardificarInforme(
        Request $request,
        AseguradorasRepository $aseguradorasRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos las Aseguradoras
        $aseguradoras = $em->getRepository(Aseguradoras::class)->findAll();
        dump($aseguradoras);

        return $this->render('aseguradoras/mostrarAseguradoras.html.twig', [
            'datosAseguradoras' => $aseguradoras,
        ]);
    }

    // Mostrar Datos de Pacientes segun busqueda introducida por Apellido con Like
    #[Route('/buscaraseguradoranombre', name: 'buscarAseguradoraNombreModificar', methods: ['GET', 'POST'])]
    public function buscarAseguradoraNombreModificar(
        Request $request,
        AseguradorasRepository $aseguradorasRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datobusqueda = $request->request->get('txtNombre');
        dump($datobusqueda);

        $query = $em->createQuery(
            'SELECT f FROM App\Entity\Facultativos f WHERE f.apellido1 like :parametro'
        );
        // Concateno la variable a buscar y el % del Like
        $query->setParameter('parametro', $datobusqueda . '%');
        dump($query);

        $aseguradoras = $query->getResult();
        dump($datos);

        return $this->render('aseguradoras/mostrarAseguradoras.html.twig', [
            'datosAseguradoras' => $aseguradoras,
        ]);
    }

    // Modificar Datos de Aseguradoras
    #[Route('/modificarinforme', name: 'modificarInforme', methods: ['GET', 'POST'])]
    public function modificarInforme(
        Request $request,
        AseguradorasRepository $aseguradorasRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Identificador de Aseguradora que me llega con Get
        $idaseguradora = $request->query->get('idaseguradora');
        dump($idaseguradora);

        // Recupero datos de informe para enviar los Values a Formulario modificacion
        $aseguradoramodificar = $em
            ->getRepository(Aseguradoras::class)
            ->findOneByIdinforme($idaseguradora);
        dump($aseguradoramodificar);

        // Creo objeto Aseguradora
        $aseguradora = new Aseguradoras();
        $formularioAseguradora = $this->createForm(
            AseguradorasType::class,
            $aseguradora
        );

        $formularioAseguradora->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioAseguradora->isSubmitted() &&
            $formularioAseguradora->isValid()
        ) {
            dump($formularioAseguradora);

            // Modificamos la Aseguradora en Base de Datos
            $em->persist($aseguradora);
            $em->flush();

            //Recupero nombre de Aseguradora modificado para el mensaje
            $nombreaseguradora = $aseguradora->getNombre();
            dump($nombreaseguradora);

            // Construimos mensaje de modificacion correcta
            $mensaje =
                'Se ha modificado la aseguradora ' .
                $nombreaseguradora .
                ' con codigo ' .
                $idaseguradora;

            // Devuelvo control a Pagina Inicio de Administrativo mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Envio al Formulario de Aseguradoras mandando el formulario
        return $this->render('aseguradoras/altaAseguradora.html.twig', [
            'aseguradoraForm' => $formularioAseguradora->createView(),
        ]);
    }
}
