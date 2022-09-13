<?php
namespace App\Controller;

use App\Entity\Citas;
use App\Form\CitasType;
use App\Repository\CitasRepository;
use App\Entity\CitasDisponibles;
use App\Form\CitasDisponiblesType;
use App\Repository\CitasDisponiblesRepository;
use App\Entity\Facultativos;
use App\Form\FacultativosType;
use App\Repository\FacultativosRepository;
use App\Entity\Especialidades;
use App\Repository\EspecialidadesRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use App\Entity\Turnos;
use App\Form\TurnosType;
use App\Repository\TurnosRepository;
use App\Entity\Vacaciones;
use App\Form\VacacionesType;
use App\Repository\VacacionesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

#[Route('/citas')]
class CitasController extends AbstractController
{
    //******************************************************************************
    // Alta/Modificacion de Citas de Paciente por parte del Administrativo *
    //******************************************************************************
    #[Route('/buscarfacultativo', name: 'buscarCitasFacultativoCitaAdmin', methods: ['GET', 'POST'])]
    public function buscarCitasFacultativoCitaAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero todos los Facultativos con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Facultativos::class)->findAll();
        dump($query);
        $datosFacultativosPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Se envia a pagina enviando los datos de los facultativos para su seleccion
        return $this->render('citas/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Facultativo por Apellido para seleccion Citas
    #[Route('/buscarfacultativoApellido', name: 'buscarFacultativoCitaApellidoAdmin', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitaApellidoAdmin(
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
            // Recupero todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.apellido1 like :parametro'
            );
            // Concateno la variable a buscar y el % del Like
            $query->setParameter('parametro', $busquedaapellido . '%');
            dump($query);
            // Al hacer el getresult ejecuta la Query y obtiene los resultados
            // $facultativos = $query->getResult();
            // dump($facultativos);
            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Recupero todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Facultativos::class)->findAll();
            dump($query);
            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        return $this->render('citas/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Facultativo por Telefono para seleccion Citas
    #[Route('/buscarfacultativoTelefono', name: 'buscarFacultativoCitaTelefonoAdmin', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitaTelefonoAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        //$busquedatelefono = $request->request->get('txtTelefono');
        $busquedatelefono = $request->query->get('txtTelefono');
        dump($busquedatelefono);

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Select de Pacientes con Where mandado por parametro con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);
            dump($query);
            // Al hacer el getresult ejecuta la Query y obtiene los resultados
            // $facultativos = $query->getResult();
            // dump($facultativos);
            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Recupero todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Facultativos::class)->findAll();
            dump($query);
            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('citas/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Mostrar todos los Pacientes para seleccionar
    #[Route('/buscarpacientecita', name: 'buscarPacienteCitaAdmin', methods: ['GET', 'POST'])]
    public function buscarPacienteCitaAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero el Facultativo ue me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Recupero todos los Pacientes con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Pacientes::class)->findAll();
        dump($query);
        $datosPacientesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Se envia a pagina enviando los datos de los pacientes
        return $this->render('citas/busquedaPaciente.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Buscar Paciente por Apellido para seleccion Citas
    #[Route('/buscarpacientecitaapellido', name: 'buscarPacienteCitaApellidoAdmin', methods: ['GET', 'POST'])]
    public function buscarPacienteCitaApellidoAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero el Facultativo que me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Recogemos datos de formulario con Get dado que es una busqueda
        // $busquedaapellido = $request->request->get('txtApellido');
        $busquedaapellido = $request->query->get('txtApellido');
        dump($busquedaapellido);

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            // Se recuperan los Pacientes con Paginacion
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

        return $this->render('citas/busquedaPaciente.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Buscar Paciente por Telefono para seleccion Citas
    #[Route('/buscarpacientecitatelefono', name: 'buscarPacienteCitaTelefonoAdmin', methods: ['GET', 'POST'])]
    public function buscarPacienteCitaTelefonoAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero el Facultativo que me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        /// Recogemos datos de formulario con Get dado que es una busqueda
        //$busquedatelefono = $request->request->get('txtTelefono');
        $busquedatelefono = $request->query->get('txtTelefono');
        dump($busquedatelefono);

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Si no se relleno se recuperan los Pacientes con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT p FROM App\Entity\Pacientes p WHERE p.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);
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

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('citas/busquedaPaciente.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Formulario para mostrar Citas si existen junto a datos de Facultativos y de Pacientes y Formulario para añadir/modificar
    #[Route('/mostrarcitasadmin', name: 'mostrarCitasAdmin', methods: ['GET', 'POST'])]
    public function mostrarCitasAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        PacientesRepository $pacientesRepository,
        TurnosRepository $turnosRepository,
        VacacionesRepository $vacacionesRepository,
        CitasRepository $citasRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero el Facultativo y el Paciente que me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Recupero datos de paciente para enviar los Values a Formulario
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);

        // Si no se relleno se recuperan todos los Pacientes con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository(Citas::class)
            ->findBy(['idpaciente' => $idpaciente], ['fecha' => 'ASC']);
        $datosCitasPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Recupero vacaciones de facultativo para tratamiento fechas disponibles
        $query = $em->createQuery(
            'SELECT v.fecha FROM App\Entity\Vacaciones v WHERE v.idfacultativo =:parametro'
        );
        // Defino el parametro
        $query->setParameter('parametro', $idfacultativo);
        $vacacionesfacultativo = $query->getResult();

        // Recupero citas disponibles de facultativo para ver si existen o se da error
        $citasdisponiblesfacultativo = $em
            ->getRepository(CitasDisponibles::class)
            ->findBy([
                'idfacultativo' => $idfacultativo,
            ]);

        // Si no existen citas disponibles se manda warning
        if (!$citasdisponiblesfacultativo) {
            $mensajewarning =
                'No se han creado citas disponibles para este facultativo';
        }
        // Si existen citas disponibles se recorre bucle para mandar fechas disponibles si quedan citas
        else {
            // Recupero Fecha del Dia y Fecha en un mes
            $fechaini = date('Y-m-d');
            $fechafin = date('Y-m-d', strtotime($fechaini . '+ 1 month'));
            $fechaactual = $fechaini;
            $fechafintramo = $fechafin;
            $arraynodisponible = [];
            while ($fechaactual <= $fechafintramo):
                // Transformo Fecha a DateTime para acceso a Entidad
                $diaconvertido = \DateTime::createFromFormat(
                    'Y-m-d',
                    $fechaactual
                );
                // Recupero dia de la semana de la fecha
                $diasemana = date('l', strtotime($fechaactual));
                // Si dia de bucle es laborable
                if ($diasemana != 'Sunday' && $diasemana != 'Saturday') {
                    // Recupero citas disponibles de ese ese facultativo que esten disponibles para el dia del bucle
                    $citasdisponiblesbucle = $em
                        ->getRepository(CitasDisponibles::class)
                        ->findBy([
                            'idfacultativo' => $idfacultativo,
                            'fecha' => $diaconvertido,
                            'disponible' => true,
                        ]);
                    // Si no se encuentran citas disponibles para ese dia se añade fecha al Array no disponible
                    if (!$citasdisponiblesbucle) {
                        $fechaarray = date('Y-m-d', strtotime($fechaactual));
                        array_push($arraynodisponible, $fechaarray);
                    }
                }
                // Añadimos los fines de semana como dias No Disponibles
                else {
                    $fechaarray = date('Y-m-d', strtotime($fechaactual));
                    array_push($arraynodisponible, $fechaarray);;
                }

                // Sumo un dia a la Fecha del bucle
                $fechaactual = date(
                    'Y-m-d',
                    strtotime($fechaactual . '+ 1 days')
                );
            endwhile;

            // Recupero en Array solo las Fechas de Vacaciones
            $vacacionesarray = array_column($vacacionesfacultativo, 'fecha');

            // Convierto array de Fechas de Vacaciones formato DateTime a Array fechas formato 'AAAA-MM-DD'
            $arrayvacaciones = [];
            foreach ($vacacionesarray as $valor) {
                $fechaarray = $valor->format('Y-m-d');
                array_push($arrayvacaciones, $fechaarray);
            }

            // Recupero de API los Festivos de la Comunidad de Madrid (fecha_festivo dara las fechas en formato Y-m-d)
            $datos = file_get_contents(
                'https://datos.comunidad.madrid/catalogo/dataset/2f422c9b-47df-407f-902d-4a2f44dd435e/resource/453162e0-bd61-4f52-8699-7ed5f33168f6/download/festivos_regionales.json'
            );
            $datosjson = json_decode($datos, true);

            // En el Array guardo los datos Json de data con los registros
            $festivosregionales = $datosjson['data'];

            // Recupero en Array solo de las Fechas de Festivos
            $festivosarray = array_column($festivosregionales, 'fecha_festivo');

            // Junto arrays de festivos con Array de Vacaciones con fechas no disponibles para enviar fechas no disponibles
            $arrayjuntarfechas = array_merge(
                $festivosarray,
                $arrayvacaciones,
                $arraynodisponible
            );
            dump($arrayjuntarfechas);
            // Eliminamos Fechas Duplicadas
            $fechasnodisponibles = array_unique($arrayjuntarfechas);
            // Ordeno Array por Fechas
            sort($fechasnodisponibles);

            // Envio a la vista de Citas, Datos Facultativo y Especialidades, Datos Pacientes, Citas y Fechas no disponibles
            return $this->render('citas/mostrarCitasAdmin.html.twig', [
                'datosFacultativo' => $facultativo,
                'datosEspecialidades' => $especialidades,
                'datosPaciente' => $paciente,
                'datosCitas' => $datosCitasPaginados,
                'fechaini' => $fechaini,
                'fechafin' => $fechafin,
                'fechadia' => $fechaini,
                'fechasnodisponibles' => $fechasnodisponibles,
            ]);
        }

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' => $mensajewarning,
        ]);
    }

        // Formulario para mostrar Citas disponibles junto a fecha seleccionada y datos de Facultativos y de Pacientes
        #[Route('/seleccionhorascitaadmin', name: 'seleccionhoraCitasAdmin', methods: ['GET', 'POST'])]
        public function seleccionhoraCitasAdmin(
            Request $request,
            FacultativosRepository $facultativosRepository,
            PacientesRepository $pacientesRepository,
            CitasDisponiblesRepository $citasDisponiblesRepository,
            EntityManagerInterface $em,
            PaginatorInterface $paginator
        ) {
            // Recupero el Facultativo y el Paciente que me llega
            // $idfacultativo = $request->request->get('idfacultativo');
            $idfacultativo = $request->query->get('idfacultativo');
            dump($idfacultativo);
            $idpaciente = $request->query->get('idpaciente');
            dump($idpaciente);
            // Recupero Fecha Seleccionada de Cita
            $fechacita = $request->query->get('txFecha');
            dump($fechacita);
    
            // Recupero datos de facultativo para enviar los Values a Formulario
            $facultativo = $em
                ->getRepository(Facultativos::class)
                ->findOneByIdfacultativo($idfacultativo);
    
            // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
            $especialidades = $em->getRepository(Especialidades::class)->findAll();
    
            // Recupero datos de paciente para enviar los Values a Formulario
            $paciente = $em
                ->getRepository(Pacientes::class)
                ->findOneByIdpaciente($idpaciente);

            // Recupero citas disponibles de ese facultativo en esa fecha
            $citasdisponiblesfacultativo = $em
                ->getRepository(CitasDisponibles::class)
                ->findBy([
                    'idfacultativo' => $idfacultativo,
                    'fecha' => '$fechacita',
                    'disponible' => 'true',
                ]);
    
            // Envio a la vista de Seleccion de Horas, Datos Facultativo-Especialidades, Datos Pacientes, Citas Disponibles
            return $this->render('citas/mostrarCitasAdmin.html.twig', [
                'datosFacultativo' => $facultativo,
                'datosEspecialidades' => $especialidades,
                'datosPaciente' => $paciente,
                'citasDisponibles' => $citasdisponiblesfacultativo,
            ]);
            
        }

    // *** ALTA
    // Recogemos Datos Formulario para borrar Citas si ya existen o Darlas de Alta si no existen
    #[Route('/altacitasadmin', name: 'altaCitasAdmin', methods: ['GET', 'POST'])]
    public function altaCitasAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Recogemos boton pulsado
        $boton = $request->request->get('operacion');

        // Recogemos datos de formulario con Post del día de la cita
        //$diacita = $request->query->get('txtfecha');
        $diacita = $request->request->get('txFecha');
        dump($diacita);
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $diacita);
        dump($diaconvertido);

        // Recogemos datos de formulario con Post de la hora de la cita
        //$horacita = $request->query->get('txtfecha');
        $horacita = $request->request->get('txHora');
        dump($horacita);
        $horaconvertida = \DateTime::createFromFormat('H:m:s', $horacita);
        dump($horaconvertida);

        // Recupero unicamente las fechas de vacaciones
        $query = $em->createQuery(
            'SELECT v.fecha FROM App\Entity\Vacaciones v WHERE v.idfacultativo =:parametro'
        );
        // Defino el parametro
        $query->setParameter('parametro', $idfacultativo);
        dump($query);
        $vacacionesfacultativo = $query->getResult();
        dump($vacacionesfacultativo);

        $mensaje = null;
        $mensajewarning = null;

        // Si se pulso el boton de Insertar
        if ($boton == 'insertar') {
            // Accedemos para ver si existe ese dia/hora de cita para el Paciente
            $existecita = $em->getRepository(Citas::class)->findOneBy([
                'idpaciente' => $idpaciente,
                'fecha' => $diaconvertido,
                'hora' => $horaconvertida,
            ]);
            // Si no existe la Cita se da de Alta
            if (!$existecita) {
                // Declaro variable de clase entidad Vacaciones
                $nuevacita = new Citas();
                // Añado valores a cada uno de los campos para el registro del Lunes
                $nuevacita->setFecha($diaconvertido);
                $nuevacita->setHora($horaconvertida);

                // Accedemos al objeto Facultativo para guardarlo con el registro de vacaciones
                $facultativo = $em
                    ->getRepository(Facultativos::class)
                    ->findOneByIdfacultativo($idfacultativo);
                dump($facultativo);
                // Accedemos al objeto Facultativo para guardarlo con el registro de vacaciones
                $paciente = $em
                    ->getRepository(Facultativos::class)
                    ->findOneByIdfacultativo($idpaciente);
                dump($paciente);

                // Añado el Facultativo
                $nuevacita->setIdfacultativo($facultativo);
                // Añado el Paciente
                $nuevacita->setIdpaciente($paciente);
                dump($nuevacita);

                // Inserto registro en la tabla de Citas
                $em->persist($nuevacita);
                $em->flush();

                $mensajedia = strtotime($diacita);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensajehora = strtotime($horacita);
                $horaformateada = date('H:M', $mensajehora);
                $mensaje =
                    'Se ha dado de alta la cita para el día ' .
                    $diaformateado .
                    ' a las ' .
                    $mensajehora .
                    ' horas para el paciente con codigo ' .
                    $idpaciente;
                // Si ya existe el dia de vacaciones se manda mensaje
            } else {
                $mensajedia = strtotime($diacita);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensajehora = strtotime($horacita);
                $horaformateada = date('H:M', $mensajehora);
                $mensajewarning =
                    'Ya existe la cita para el día ' .
                    $diaformateado .
                    ' a las ' .
                    $mensajehora .
                    ' horas para el paciente con codigo ' .
                    $idpaciente;
            }
        }

        // Si se pulso el boton de Borrar Cita
        if ($boton == 'eliminar') {
            // Accedemos para ver si existe esa cita para el paciente
            $existecita = $em->getRepository(Citas::class)->findOneBy([
                'idpaciente' => $idpaciente,
                'fecha' => $diaconvertido,
                'hora' => $horaconvertida,
            ]);
            // Si existe la cita se da de Baja
            if ($existecita) {
                // Realizamos Baja del la cita con esa fecha y hora
                $em->remove($existecita);
                $em->flush();

                $mensajedia = strtotime($diaconvertido);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensajehora = strtotime($horacita);
                $horaformateada = date('H:M', $mensajehora);
                $mensaje =
                    'Se ha dado de baja la cita para el día ' .
                    $diaformateado .
                    ' a las ' .
                    $mensajehora .
                    ' horas para el paciente con codigo ' .
                    $idpaciente;
                // Si no existe la cita se manda mensaje de error
            } else {
                $mensajedia = strtotime($diaconvertido);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensajehora = strtotime($horacita);
                $horaformateada = date('H:M', $mensajehora);
                $mensajewarning =
                    'No exixte la cita para el día ' .
                    $diaformateado .
                    ' a las ' .
                    $mensajehora .
                    ' horas para el paciente con codigo ' .
                    $idpaciente;
            }
        }

        // // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        // return $this->render('dashboard/dashboardAdministrativo.html.twig', [
        //     'mensaje' => $mensaje,
        // ]);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Recupero datos de paciente para enviar los Values a Formulario
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdfacultativo($idpaciente);
        dump($paciente);

        // Recupero citas de paciente ordenadas por fecha para enviar los Values a Formulario
        $citaspaciente = $em
            ->getRepository(Vacaciones::class)
            ->findBy(['idpaciente' => $idpaciente], ['fecha' => 'ASC']);
        dump($citaspaciente);

        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');
        dump($fechadia);
        // Recuperar Año y mes del día
        $anio = date('Y');
        dump($anio);
        $mes = date('m');
        dump($mes);
        $fechaini = $anio . '-' . $mes . '-01';
        dump($fechaini);

        // $fechafin = date_add(
        //     $fechaini,
        //     date_interval_create_from_date_string('2 months')
        // );
        // dump($fechafin);

        // Recupero de API los Festivos de la Comunidad de Madrid (fecha_festivo dara las fechas en formato Y-m-d)
        $datos = file_get_contents(
            'https://datos.comunidad.madrid/catalogo/dataset/2f422c9b-47df-407f-902d-4a2f44dd435e/resource/453162e0-bd61-4f52-8699-7ed5f33168f6/download/festivos_regionales.json'
        );
        $datosjson = json_decode($datos, true);
        dump($datosjson);

        // En el Array guardo los datos Json de data con los registros
        $festivosregionales = $datosjson['data'];
        dump($festivosregionales);
        // Recupero en Array solo de las Fechas de Festivos
        $festivosarray = array_column($festivosregionales, 'fecha_festivo');
        dump($festivosarray);
        // Recupero en Array solo las Fechas de Vacaciones
        $vacacionesarray = array_column($vacacionesfacultativo, 'fecha');
        dump($vacacionesarray);

        // Junto arrays de festivos con Array de Vacaciones para enviar fechas no disponibles
        $fechasnodisponibles = array_push($festivosarray, $vacacionesarray);
        dump($fechasnodisponibles);

        // Envio a la vista de Vacaciones, Datos Facultativo y Especialidades, Datos Pacientes, Citas, Fechas no disponibles y Mensajes
        return $this->render('vacaciones/altaCitasAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPaciente' => $paciente,
            'datosCitas' => $citaspaciente,
            'fechaini' => $fechaini,
            // 'fechafin' => $fechafin,
            'fechadia' => $fechadia,
            'fechasnodisponibles' => $fechasnodisponibles,
            'mensaje' => $mensaje,
            'mensajewarning' => $mensajewarning,
        ]);
    }
}
