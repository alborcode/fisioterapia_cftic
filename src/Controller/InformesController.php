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

#[Route('/informes')]
class InformesController extends AbstractController
{
    //**********************************************
    // Alta de Informe, con Paciente y Facultativo *
    //**********************************************
    #[Route('/buscarpacientealta', name: 'buscarpacienteInformeAlta', methods: ['GET', 'POST'])]
    public function buscarpacienteInformeAlta(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos los Pacientes para seleccionar el que se quiere dar Informe de alta
        $pacientes = $em->getRepository(Pacientes::class)->findAll();

        return $this->render('facultativos/mostrarPacientesInforme.html.twig', [
            'datosPacientes' => $pacientes,
        ]);
    }

    // Mostrar Datos de Pacientes segun busqueda introducida por Apellido con Like
    #[Route('/buscarpacienteapellido', name: 'buscarPacienteInformeApellido', methods: ['GET', 'POST'])]
    public function buscarPacienteInformeApellido(
        Request $request,
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

        return $this->render('facultativos/mostrarPacientesInforme.html.twig', [
            'datosPacientes' => $pacientes,
        ]);
    }

    // Mostrar Datos de Pacientes segun busqueda unica por telefono
    #[Route('/buscarpacientetelefono', name: 'buscarPacienteInformeTelefono')]
    public function buscarPacienteInformeTelefono(
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

        return $this->render('facultativos/mostrarPacientesInforme.html.twig', [
            'datosPacientes' => $pacientes,
        ]);
    }

    // Seleccion de Paciente Formulario de Informe
    #[Route('/altainforme', name: 'altaInforme', methods: ['GET', 'POST'])]
    public function altaInforme(
        Request $request,
        FacultativosRepository $facultativosRepository,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variables de sesion del Facultativo conectado
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);
        // Recupero el Paciente que me llega con Get (por seguridad deberia ser con Post no con Get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero la fecha del dia para mandar value
        $fechadia = new Date();
        dump('fecha del dia:' . $fechadia);
        $fechadiaformat = $fechadia->format('dd-mm-Y');
        dump('fecha del dia formateada:' . $fechadiaformat);

        // Creo objeto Informe
        $informe = new Informes();
        $formularioInforme = $this->createForm(InformesType::class, $informe);

        $formularioInforme->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioInforme->isSubmitted() &&
            $formularioInforme->isValid()
        ) {
            dump($formularioInforme);

            // Modificamos los valores con los datos del Formulario, el ID de Informe no se puede modificar es clave
            // $informe->setIdpaciente($idpaciente);
            // $informe->setFecha($fecha);

            // Accedo a Objeto Paciente y Facultativo para guardarlos
            // Recupero datos de usuario para enviar los Values a Formulario
            $objetopaciente = $em
                ->getRepository(Pacientes::class)
                ->findOneByIdusuario($idpaciente);
            dump($objetopaciente);
            // Recupero datos de paciente para enviar los Values a Formulario
            $objetofacultativo = $em
                ->getRepository(Facultativos::class)
                ->findOneByIdfacultativo($idfacultativo);
            dump($objetofacultativo);

            // Guardo los objetos Paciente y Facultativo antes de dar de alta el Informe
            $informe->setIdpaciente($objetopaciente);
            $informe->setIdfacultativo($objetofacultativo);
            dump($informe);

            // Damos de Alta el Informe en Base de Datos
            $em->persist($informe);
            $em->flush();
            //Recupero el identificador de Informe para el mensaje
            $idinforme = $informe->getIdinforme();
            //dump($idinforme);

            // Construimos mensaje de alta correcta
            $mensaje = 'Se ha dado de alta el informe ' . $idinforme;

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

    //*********************************************************
    // Modificar Informe, con Informe, Paciente y Facultativo *
    //*********************************************************
    #[Route('/buscarpacientemodificar', name: 'buscarpacienteModificarInforme', methods: ['GET', 'POST'])]
    public function buscarpacienteModificarInforme(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos los Informes de Todos los Pacientes
        // La funcion del Repositorio hace un Inner Join sin Where
        $informes = $em
            ->getRepository(Informes::class)
            ->findAllInformesPaciente();
        // Query con InnerJoin para unir los datos de pacientes con sus informes
        $query = $em->createQuery(
            'SELECT i.idinforme, i.fecha, i.tipoinforme, i.detalle, i.idfacultativo, i.idpaciente , p.telefono, p.nombre, p.apellido1, p.apellido2 
            FROM App\Entity\Informes i 
            INNER JOIN App\Entity\Pacientes p ON i.idpaciente = p.idpaciente
            ORDER BY i.idpaciente'
        );
        // SELECT Clientes.NombreCliente, Pedidos.PedidoID FROM Clientes
        // INNER JOIN Pedidos ON Clientes.ClienteID=Pedidos.ClienteID
        // ORDER BY Clientes.NombreCliente;

        return $this->render(
            'facultativos/mostrarPacientesMInforme.html.twig',
            [
                'datosInformes' => $informes,
            ]
        );
    }

    // Mostrar Datos de Pacientes segun busqueda introducida por Apellido con Like
    #[Route('/buscarpacienteapellidomodificar', name: 'buscarPacienteApellidoModificarInforme', methods: ['GET', 'POST'])]
    public function buscarPacienteApellidoModificarInforme(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datobusqueda = $request->request->get('txtApellido');
        dump($datobusqueda);

        // Query con InnerJoin para unir los datos de pacientes con sus informes de aquellos Pacientes cuyo apellido sea el Like
        $query = $em->createQuery(
            'SELECT i.idinforme, i.fecha, i.tipoinforme, i.detalle, i.idfacultativo, i.idpaciente , p.telefono, p.nombre, p.apellido1, p.apellido2 
            FROM App\Entity\Informes i 
            INNER JOIN App\Entity\Pacientes p ON i.idpaciente = p.idpaciente
            WHERE p.apellido1 like :parametro
            ORDER BY i.idpaciente'
        );
        // Concateno la variable a buscar y el % del Like
        $query->setParameter('parametro', $datobusqueda . '%');
        dump($query);

        $pacientes = $query->getResult();
        dump($datos);

        return $this->render(
            'facultativos/mostrarPacientesMInforme.html.twig',
            [
                'datosInformes' => $informes,
            ]
        );
    }

    // Mostrar Datos de Pacientes segun busqueda introducida por Telefono
    #[Route('/buscarpacientetelefonomodificar', name: 'buscarPacienteTelefonoModificarInforme', methods: ['GET', 'POST'])]
    public function buscarPacienteTelefonoModificarInforme(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datoget = $request->request->get('txtTelefono');
        dump($datoget);

        // Query con InnerJoin para unir los datos de pacientes con sus informes de aquellos Pacientes cuyo telefono se haya introducido
        $query = $em->createQuery(
            'SELECT i.idinforme, i.fecha, i.tipoinforme, i.detalle, i.idfacultativo, i.idpaciente , p.telefono, p.nombre, p.apellido1, p.apellido2 
            FROM App\Entity\Informes i 
            INNER JOIN App\Entity\Pacientes p ON i.idpaciente = p.idpaciente
            WHERE p.telefono = :parametro
            ORDER BY i.idpaciente'
        );
        // Asigno valor del parametro dato
        $query->setParameter('dato', $datoget);
        dump($query);

        $pacientes = $query->getResult();
        dump($pacientes);

        return $this->render(
            'facultativos/mostrarPacientesMInforme.html.twig',
            [
                'datosInformes' => $informes,
            ]
        );
    }

    // Mostrar Datos para seleccionar el paciente e informe
    #[Route('/modificarinforme', name: 'modificarInforme', methods: ['GET', 'POST'])]
    public function modificarInforme(
        Request $request,
        FacultativosRepository $facultativosRepository,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variables de sesion del Facultativo conectado
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);
        // Recupero el Paciente que me llega con Get (por seguridad deberia ser con Post no con Get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);
        // Recupero el Informe que me llega con Get (por seguridad deberia ser con Post no con Get)
        $idinforme = $request->query->get('idinforme');
        dump($idinforme);

        // Recupero datos de informe para enviar los Values a Formulario modificacion
        $informemodificar = $em
            ->getRepository(Informes::class)
            ->findOneByIdinforme($idinforme);
        dump($informemodificar);
        // No hace falta enviar ni paciente ni facultativo dado que no se modificaran

        // Creo objeto Informe
        $informe = new Informes();
        $formularioInforme = $this->createForm(InformesType::class, $informe);

        $formularioInforme->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioInforme->isSubmitted() &&
            $formularioInforme->isValid()
        ) {
            dump($formularioInforme);
            // $fecha = $request->query->get('fecha');
            // Modificamos los valores con los datos del Formulario, el ID de Informe no se puede modificar es clave
            // $informe->setIdpaciente($idpaciente);
            // $informe->setFecha($fecha);
            // No se Accede a Objeto Paciente y Facultativo ya que no se modifican

            // Modificamos el Informe en Base de Datos
            $em->persist($informe);
            $em->flush();

            // Construimos mensaje de alta correcta
            $mensaje = 'Se ha modificado el informe ' . $idinforme;

            // Devuelvo control a Pagina Inicio de Facultativo mandando mensaje
            return $this->render('dashboard/dashboardFacultativo.html.twig', [
                'mensaje' => $mensaje,
            ]);
        }

        // Envio al Formulario de Informe mandando el formulario, el idpaciente y fecha del dia
        return $this->render('informes/altaInforme.html.twig', [
            'perfilPacienteForm' => $formularioPerfilPaciente->createView(),
            'datosInforme' => $informemodificar,
        ]);
    }
}
