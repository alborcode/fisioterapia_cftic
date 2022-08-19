<?php
namespace App\Controller;

use App\Entity\Pacientes;
use App\Repository\PacientesRepository;
use App\Entity\Facultativos;
use App\Repository\FacultativosRepository;
use App\Entity\Informes;
use App\Repository\InformesRepository;
use App\Form\InformesType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rehabilitaciones')]
class RehabilitacionesController extends AbstractController
{
    //***************************************************************
    // Alta de Rehabilitacion, con Paciente y Combo con Aseguradora *
    //***************************************************************
    // Previamente hay que dar de alta aseguradoras y de alta al Paciente que tendra rehabilitacion
    #[Route('/buscarpacientealta', name: 'buscarpacienteRehabilitacionAlta', methods: ['GET', 'POST'])]
    public function buscarpacienteRehabilitacionAlta(
        Request $request,
        PacientesRepository $pacientesRepository,
        RehabilitacionesRepository $rehabilitacionesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos los Pacientes para seleccionar el que se quiere dar Rehabilitacion de alta
        $pacientes = $em->getRepository(Pacientes::class)->findAll();

        return $this->render(
            'facultativos/mostrarPacientesRehabilitacion.html.twig',
            [
                'datosPacientes' => $pacientes,
            ]
        );
    }

    // Mostrar Datos de Pacientes segun busqueda introducida por Apellido con Like
    #[Route('/buscarpacienteapellido', name: 'buscarPacienteRehabilitacionApellido', methods: ['GET', 'POST'])]
    public function buscarPacienteRehabilitacionApellido(
        Request $request,
        PacientesRepository $pacientesRepository,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datobusqueda = $request->request->get('txtApellido');
        dump($datobusqueda);

        // Query con Like por Apellido Paciente
        $query = $em->createQuery(
            'SELECT f FROM App\Entity\Pacientes f WHERE f.apellido1 like :parametro'
        );
        // Concateno la variable a buscar y el % del Like
        $query->setParameter('parametro', $datobusqueda . '%');
        dump($query);

        $pacientes = $query->getResult();
        dump($datos);

        return $this->render(
            'facultativos/mostrarPacientesRehabilitacion.html.twig',
            [
                'datosPacientes' => $pacientes,
            ]
        );
    }

    // Mostrar Datos de Pacientes segun busqueda unica por telefono
    #[Route('/buscarpacientetelefono', name: 'buscarPacienteRehabilitacionTelefono')]
    public function buscarPacienteRehabilitacionTelefono(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post, query si es get
        $datoget = $request->request->get('txtTelefono');
        // Query con Paciente que coincida con numero de telefono
        $query = $em->createQuery(
            'SELECT f FROM App\Entity\Pacientes f WHERE f.telefono = :dato'
        );
        // Asigno valor del parametro dato
        $query->setParameter('dato', $datoget);
        // Al hacer el getresult ejecuta la Query y obtiene los resultados
        $pacientes = $query->getResult();

        return $this->render(
            'facultativos/mostrarPacientesRehabilitacion.html.twig',
            [
                'datosPacientes' => $pacientes,
            ]
        );
    }

    // Seleccion de Paciente Formulario de Rehabilitacion
    #[Route('/altarehabilitacion', name: 'altaRehabilitacion', methods: ['GET', 'POST'])]
    public function altaRehabilitacion(
        Request $request,
        FacultativosRepository $facultativosRepository,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // No hace falta recuperar el facultativo dado que sera el rehabilitador libre
        // Recupero las variables de sesion del Facultativo conectado
        // $idfacultativo = $request->getSession()->get('idfacultativo');
        // dump($idfacultativo);
        // Recupero el Paciente que me llega con Get (por seguridad deberia ser con Post no con Get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero la fecha del dia para mandar value
        $fechadia = new Date();
        dump('fecha del dia:' . $fechadia);
        $fechadiaformat = $fechadia->format('dd-mm-Y');
        dump('fecha del dia formateada:' . $fechadiaformat);

        // Creo objeto Rehabilitacion
        $rehabilitacion = new Rehabilitaciones();
        $formularioRehabilitacion = $this->createForm(
            RehabilitacionesType::class,
            $rehabilitacion
        );

        $formularioRehabilitacion->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioRehabilitacion->isSubmitted() &&
            $formularioRehabilitacion->isValid()
        ) {
            dump($formularioRehabilitacion);
            // Recogemos los campos del Formulario en Array para tratarlos
            $dataformulario = $form->getData();
            $sesionestotales = $request->query->get('sesionestotales');
            //$sesionesrestantes = $request->query->get('sesionestotales');
            //$fechainicio = $request->query->get('fechainicio');

            // Modificamos los valores con los datos del Formulario, el ID de Informe no se puede modificar es clave
            // $informe->setIdpaciente($idpaciente);
            //$rehabilitacion->setFechainicio($fechainicio);
            $rehabilitacion->setSesionesrestantes($sesionestotales);

            // Accedo a Objeto Paciente para guardarlo
            // Recupero datos de paciente para enviar los Values a Formulario
            $objetopaciente = $em
                ->getRepository(Pacientes::class)
                ->findOneByIdusuario($idpaciente);
            dump($objetopaciente);

            // Guardo el objeto Paciente antes de dar de alta la Rehabilitacion
            $rehabilitacion->setIdpaciente($objetopaciente);
            dump($rehabilitacion);

            // Damos de Alta la rehabilitacion en Base de Datos
            $em->persist($rehabilitacion);
            $em->flush();
            //Recupero el identificador de Rehabilitacion para el mensaje
            $idrehabilitacion = $rehabilitacion->getIdrehabilitacion();
            dump($idrehabilitacion);

            // Construimos mensaje de alta correcta
            $mensaje =
                'Se ha dado de alta la rehabilitacion ' .
                $idrehabilitacion .
                ' del paciente ' .
                $idpaciente;

            // Devuelvo control a Pagina Inicio de Facultativo mandando mensaje
            return $this->render('dashboard/dashboardFacultativo.html.twig', [
                'mensaje' => $mensaje,
            ]);
        }

        // Envio al Formulario de Informe mandando el formulario, el idpaciente y fecha del dia
        return $this->render('informes/modificarPerfil.html.twig', [
            'perfilPacienteForm' => $formularioPerfilPaciente->createView(),
            'idPaciente' => $idpaciente,
            '$fechadia' => $fechadiaformat,
        ]);
    }

    //*******************************************************
    // Alta de Sesion, con ultima Rehabilitacion y Paciente *
    //*******************************************************
    #[Route('/buscarsesionaltasesion', name: 'buscarsesionAltaSesion', methods: ['GET', 'POST'])]
    public function buscarsesionAltaSesion(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos las Rehabilitaciones de Todos los Pacientes
        // La funcion del Repositorio hace un Inner Join sin Where
        $informes = $em
            ->getRepository(Informes::class)
            ->findAllInformesPaciente();
        // Query con InnerJoin para unir los datos de pacientes con sus informes
        $query = $em->createQuery(
            'SELECT r.idrehabilitacion, r.idpaciente, r.fechainicio, r.ultimasesion, r.sesionestotales, r.sesionesrestantes, p.telefono, p.nombre, p.apellido1, p.apellido2 
            FROM App\Entity\Rehabilitaciones r 
            INNER JOIN App\Entity\Pacientes p ON r.idpaciente = p.idpaciente
            ORDER BY r.idpaciente ASC, r.idrehabilitacion ASC'
        );
        // SELECT Clientes.NombreCliente, Pedidos.PedidoID FROM Clientes
        // INNER JOIN Pedidos ON Clientes.ClienteID=Pedidos.ClienteID
        // ORDER BY Clientes.NombreCliente;

        return $this->render('facultativos/mostrarPacientesSesion.html.twig', [
            'datosInformes' => $informes,
        ]);
    }

    // Mostrar Datos de Rehabilitaciones segun busqueda introducida por Apellido con Like
    #[Route('/buscarsesionapellido', name: 'buscarSesionApellido', methods: ['GET', 'POST'])]
    public function buscarSesionApellido(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datobusqueda = $request->request->get('txtApellido');
        dump($datobusqueda);

        // Query con InnerJoin para unir los datos de pacientes con sus informes de aquellos Pacientes cuyo apellido sea el Like
        $query = $em->createQuery(
            'SELECT r.idrehabilitacion, r.idpaciente, r.fechainicio, r.ultimasesion, r.sesionestotales, r.sesionesrestantes, p.telefono, p.nombre, p.apellido1, p.apellido2 
            FROM App\Entity\Rehabilitaciones r 
            INNER JOIN App\Entity\Pacientes p ON r.idpaciente = p.idpaciente
            WHERE p.apellido1 like :parametro
            ORDER BY r.idpaciente ASC, r.idrehabilitacion ASC'
        );
        // Concateno la variable a buscar y el % del Like
        $query->setParameter('parametro', $datobusqueda . '%');
        dump($query);

        $pacientes = $query->getResult();
        dump($datos);

        return $this->render('facultativos/mostrarPacientesSesion.html.twig', [
            'datosInformes' => $informes,
        ]);
    }

    // Mostrar Datos de Rehabilitaciones segun busqueda introducida por Telefono
    #[Route('/buscarsesiontelefono', name: 'buscarSesionTelefono', methods: ['GET', 'POST'])]
    public function buscarSesionTelefono(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datoget = $request->request->get('txtTelefono');
        dump($datoget);

        // Query con InnerJoin para unir los datos de pacientes con sus informes de aquellos Pacientes cuyo telefono se haya introducido
        $query = $em->createQuery(
            'SELECT r.idrehabilitacion, r.idpaciente, r.fechainicio, r.ultimasesion, r.sesionestotales, r.sesionesrestantes, p.telefono, p.nombre, p.apellido1, p.apellido2 
            FROM App\Entity\Rehabilitaciones r 
            INNER JOIN App\Entity\Pacientes p ON r.idpaciente = p.idpaciente
            WHERE p.telefono = :parametro
            ORDER BY r.idpaciente ASC, r.idrehabilitacion ASC'
        );
        // Asigno valor del parametro dato
        $query->setParameter('dato', $datoget);
        dump($query);

        $pacientes = $query->getResult();
        dump($pacientes);

        return $this->render('facultativos/mostrarPacientesSesion.html.twig', [
            'datosInformes' => $informes,
        ]);
    }

    // Mostrar Datos de Ultima Sesion de Rehabilitacion de Paciente y Formulario Alta de Nueva Sesion
    #[Route('/altasesion', name: 'altaSesion', methods: ['GET', 'POST'])]
    public function mostrarUltimaSesion(
        Request $request,
        RehabilitacionRepository $rehabilitacionesRepository,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Paciente que me llega con Get (por seguridad deberia ser con Post no con Get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero Datos de Paciente
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);
        // Recupero ultima sesion de rehabilitacion de ese Paciente
        $ultimasesion = $em
            ->getRepository(Rehabilitaciones::class)
            ->findMaxSesionPaciente($idpaciente);
        dump($ultimasesion);
        // Recupero datos ultima sesion de rehabilitacion de ese Paciente
        $ultimarehabilitacion = $em
            ->getRepository(Rehabilitaciones::class)
            ->findByIdrehabilitacion($ultimasesion);
        dump($ultimarehabilitacion);
        // Recupero todas las rehabilitaciones de ese Paciente para mostrar historial
        $rehabilitaciones = $em
            ->getRepository(Rehabilitaciones::class)
            ->findByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero la fecha del dia para mandar value
        $fechadia = new Date();
        dump('fecha del dia:' . $fechadia);
        $fechadiaformat = $fechadia->format('dd-mm-Y');
        dump('fecha del dia formateada:' . $fechadiaformat);

        // Creo objeto Rehabilitacion
        $rehabilitacion = new Rehabilitaciones();
        // Declaro formulario de Sesion que no va ligado a Entidad por lo que no pongo el Type de Class
        $formularioSesion = $this->createForm($rehabilitacion);

        $formularioSesion->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if ($formularioSesion->isSubmitted() && $formularioSesion->isValid()) {
            dump($formularioSesion);
            // Recogemos los campos del Formulario en Array para tratarlos
            $dataformulario = $formularioSesion->getData();
            dump($dataformulario);

            $sesionestotales = $request->query->get('sesionestotales');
            $fechainicio = $request->query->get('fechainicio');
            $ultimasesion = $request->query->get('ultimasesion');
            $observaciones = $request->query->get('observaciones');
            $aseguradora = $request->query->get('idaseguradora');

            // Modificamos los valores con los datos de ultima Sesion con Formulario, el ID de Informe no se manda dado que es clave
            // $informe->setIdpaciente($idpaciente);
            $rehabilitacion->setFechainicio($fechainicio);
            $rehabilitacion->setUltimasesion($ultimasesion);
            $rehabilitacion->setSesionestotales($sesionestotales);
            $rehabilitacion->setIdaseguradora($aseguradora);
            $sesionesrestantes = $sesionestotales - 1;
            if ($sesionesrestantes < 0) {
                $sesionesrestantes = 0;
            }
            $rehabilitacion->setSesionesrestantes($sesionesrestantes);
            $rehabilitacion->setObservaciones($observaciones);
            $rehabilitacion->setAnexo($anexo);

            // Se Accede a Objeto Paciente y Aseguradora ya que no se modifican
            $ultimopaciente = $ultimarehabilitacion->getIdpaciente();
            $ultimaaseguradora = $ultimarehabilitacion->getIdaseguradora();
            // Modificar datos con los datos de ultima sesion
            $rehabilitacion->setIdpaciente($ultimopaciente);
            //$rehabilitacion->setIdaseguradora($ultimaaseguradora);

            // Modificamos el Informe en Base de Datos
            $em->persist($rehabilitacion);
            $em->flush();

            // Construimos mensaje de alta correcta
            $mensaje =
                'Se ha añadido una nueva sesion de rehabilitacion con el codigo ' .
                $idrehabilitacion;

            // Devuelvo control a Pagina Inicio de Facultativo mandando mensaje
            return $this->render('dashboard/dashboardFacultativo.html.twig', [
                'mensaje' => $mensaje,
            ]);
        }

        return $this->render('facultativos/mostrarPacientesSesion.html.twig', [
            'datosPaciente' => $paciente,
            'datosRehabilitacion' => $rehabilitaciones,
            'datosultimaSesionPaciente' => $ultimarehabilitacion,
            '$fechadia' => $fechadiaformat,
        ]);
    }
}
