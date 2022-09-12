<?php
namespace App\Controller;

use App\Entity\Pacientes;
use App\Repository\PacientesRepository;
use App\Entity\Facultativos;
use App\Repository\FacultativosRepository;
use App\Entity\Especialidades;
use App\Repository\EspecialidadesRepository;
use App\Entity\Informes;
use App\Repository\InformesRepository;
use App\Form\InformesType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

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
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero todos los Pacientes para seleccionar el que se quiere dar Informe de alta con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Pacientes::class)->findAll();
        dump($query);
        $datosPacientesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        return $this->render('informes/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Buscar Perfil Paciente por Apellido
    #[Route('/buscarperfilpacienteApellido', name: 'buscarPerfilPacienteApellido', methods: ['GET', 'POST'])]
    public function buscarPerfilPacienteApellido(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        // $busquedaapellido = $request->request->get('txtApellido');
        $busquedaapellido = $request->query->get('txtApellido');
        dump($busquedaapellido);

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            // Recupero todos los Pacientes para seleccionar el que se quiere dar Informe de alta con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT p FROM App\Entity\Pacientes p WHERE p.apellido1 like :parametro'
            );
            // Concateno la variable a buscar y el % del Like
            $query->setParameter('parametro', $busquedaapellido . '%');
            dump($query);
            // Al hacer el getresult ejecuta la Query y obtiene los resultados
            // $pacientes = $query->getResult();
            // dump($pacientes);
            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Si no se relleno se recuperan todos los Pacientes con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Pacientes::class)->findAll();
            dump($query);
            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        return $this->render('informes/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Buscar Perfil Paciente por Telefono
    #[Route('/buscarperfilpacienteTelefono', name: 'buscarPerfilPacienteTelefono', methods: ['GET', 'POST'])]
    public function buscarPerfilPacienteTelefono(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        //$busquedatelefono = $request->request->get('txtTelefono');
        $busquedatelefono = $request->query->get('txtTelefono');
        dump($busquedatelefono);

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Si no se relleno se recuperan todos los Pacientes con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT p FROM App\Entity\Pacientes p WHERE p.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);
            dump($query);
            // Al hacer el getresult ejecuta la Query y obtiene los resultados
            // $pacientes = $query->getResult();
            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Si no se relleno se recuperan todos los Pacientes con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Pacientes::class)->findAll();
            dump($query);
            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('informes/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    #[Route('/mostrarfacultativopaciente', name: 'mostrarDatosFacultativoPaciente', methods: ['GET', 'POST'])]
    public function mostrarDatosFacultativoPaciente(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variable de sesion de facultativo
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);

        // Recupero el paciente que se envia
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero datos de paciente para enviar los Values a Formulario
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Recupero Fecha del Dia
        $fechadia = new \DateTime('@' . strtotime('now'));
        dump($fechadia);

        // Envio a la vista de Datos Perfil Paciente
        return $this->render('informes/altaInforme.html.twig', [
            'datosPaciente' => $paciente,
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'fechadia' => $fechadia,
        ]);
    }

    // Recogemos Datos Formulario para dar el Alta del Informe
    #[Route('/altainfome', name: 'altaInforme', methods: ['GET', 'POST'])]
    public function altaInforme(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero las variable de sesion de facultativo
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);

        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero datos de objeto Paciente con el idpaciente
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero datos de objeto Facultativo con el idfacultativo
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recogemos datos de formulario
        $fecha = $request->request->get('txtFechaInforme');
        dump($fecha);
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $fecha);
        dump($diaconvertido);
        $tipoinforme = $request->request->get('comboTipoInforme');
        dump($tipoinforme);
        $observaciones = $request->request->get('txtObservaciones');
        dump($observaciones);

        // Declaro variable de clase entidad Informe
        $nuevoinforme = new Informes();

        // Modificamos los valores del Informe con los datos del Formulario, el ID no se puede modificar es clave
        // $nuevoinforme->setIdinforme($idinforme);
        $nuevoinforme->setFecha($diaconvertido);
        $nuevoinforme->setTipoinforme($tipoinforme);
        $nuevoinforme->setDetalle($observaciones);

        // Guardo el pacientes antes de guardar el Informe con el objeto paciente
        $nuevoinforme->setIdpaciente($paciente);
        // Guardo el facultativo antes de guardar el Informe con el objeto facultativo
        $nuevoinforme->setIdfacultativo($facultativo);
        dump($nuevoinforme);

        // Insertamos el Informe
        $em->persist($nuevoinforme);
        $em->flush();

        // Construimos mensaje de alta correcta
        $mensaje =
            'Se ha añadido un nuevo Informe para el Paciente ' . $idpaciente;

        // Recupero todos los Pacientes para enviar al formulario
        // $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Pacientes::class)->findAll();
        dump($query);
        $datosPacientesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('informes/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
            'mensaje' => $mensaje,
        ]);
    }

    //*********************************************
    // Mostrar Listado de Informes de un Paciente *
    //*********************************************
    #[Route('/mostrarlistadoinformes', name: 'mostrarListadoInformes', methods: ['GET', 'POST'])]
    public function mostrarListadoInformes(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recupero datos de objeto Paciente con el idpaciente
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Si no se relleno se recuperan todos los Informes del Pacientes con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository(Informes::class)
            ->findByIdpaciente($idpaciente);
        dump($query);
        $datosInformesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        return $this->render('informes/mostrarlistadoinformes.html.twig', [
            'datosPaciente' => $paciente,
            'datosInformes' => $datosInformesPaginados,
        ]);
    }

    //********************************************
    // Mostrar Detalle de Informe de un Paciente *
    //********************************************
    #[Route('/detalleinforme', name: 'detalleInforme', methods: ['GET', 'POST'])]
    public function detalleInforme(
        Request $request,
        InformesRepository $informesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variable de sesion de facultativo
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);

        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);
        $idinforme = $request->query->get('idinforme');
        dump($idinforme);

        // Recupero datos de objeto Paciente con el idpaciente
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero el Informe del Paciente
        $informe = $em
            ->getRepository(Informes::class)
            ->findOneByIdinforme($idinforme);
        dump($informe);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        return $this->render('informes/detalleInforme.html.twig', [
            'datosPaciente' => $paciente,
            'datosInforme' => $informe,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    //*********************************************************
    // Modificar Informe, con Informe, Paciente y Facultativo *
    //*********************************************************
    #[Route('/formulariomodificar', name: 'formularioModificar', methods: ['GET', 'POST'])]
    public function formularioModificar(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variable de sesion de facultativo
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);

        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);
        $idinforme = $request->query->get('idinforme');
        dump($idinforme);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero datos de paciente para enviar los Values a Formulario
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero datos de Informe para enviar los Values a Formulario
        $informe = $em
            ->getRepository(Informes::class)
            ->findOneByIdinforme($idinforme);
        dump($informe);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Envio a la vista de Datos
        return $this->render('informes/modificarInforme.html.twig', [
            'datosPaciente' => $paciente,
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosInforme' => $informe,
        ]);
    }

    // Recogemos Datos Formulario para Modificar el Informe
    #[Route('/modificarinfome', name: 'modificarInforme', methods: ['GET', 'POST'])]
    public function modificarInforme(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero las variable de sesion de facultativo
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idfacultativo);

        // Recupero Parametros con Get
        $idinforme = $request->query->get('idinforme');
        dump($idinforme);
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero el Informe a modificar con el idinforme
        $modificarinforme = $em
            ->getRepository(Informes::class)
            ->findOneByIdinforme($idinforme);
        dump($modificarinforme);

        // Recogemos el idpaciente del Informe recuperado
        //$idpaciente = $modificarinforme->getIdpaciente();

        // Recogemos datos de formulario
        // $fecha = $request->request->get('txtFechaInforme');
        // dump($fecha);
        // $diaconvertido = \DateTime::createFromFormat('Y-m-d', $fecha);
        // dump($diaconvertido);
        $tipoinforme = $request->request->get('comboTipoInforme');
        dump($tipoinforme);
        $observaciones = $request->request->get('txtObservaciones');
        dump($observaciones);

        // Modificamos los valores del Informe con los datos del Formulario, el ID no se puede modificar es clave
        // $modificarinforme->setIdinforme($idinforme);
        // $modificarinforme->setFecha($diaconvertido);
        $modificarinforme->setTipoinforme($tipoinforme);
        $modificarinforme->setDetalle($observaciones);
        dump($modificarinforme);

        // Modificamos el Informe
        $em->persist($modificarinforme);
        $em->flush();

        // Construimos mensaje de modificacion correcta
        $mensaje =
            'Se ha modificado el informe ' .
            $idinforme .
            ' para el Paciente ' .
            $idpaciente;

        // Recupero datos de objeto Paciente con el idpaciente
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero todos los Informes del Paciente con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository(Informes::class)
            ->findByIdpaciente($idpaciente);
        dump($query);
        $datosInformesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            10 // Número de elementos por página
        );

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('informes/mostrarlistadoinformes.html.twig', [
            'datosPaciente' => $paciente,
            'datosInformes' => $datosInformesPaginados,
            'mensaje' => $mensaje,
        ]);
    }
}
