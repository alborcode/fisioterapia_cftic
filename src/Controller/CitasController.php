<?php
namespace App\Controller;

use App\Entity\Citas;
use App\Repository\CitasRepository;
use App\Entity\CitasDisponibles;
use App\Repository\CitasDisponiblesRepository;
use App\Entity\Facultativos;
use App\Repository\FacultativosRepository;
use App\Entity\Especialidades;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Pacientes;
use App\Repository\PacientesRepository;
use App\Repository\TurnosRepository;
use App\Repository\VacacionesRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

// Espacios de Nombres de la Biblioteca Dompdf
use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/citas')]
class CitasController extends AbstractController
{
    //**********************************************************************
    // Alta/Modificacion de Citas de Paciente por parte del Administrativo *
    //**********************************************************************
    #[Route('/buscarfacultativo', name: 'buscarCitasFacultativoCitaAdmin', methods: ['GET', 'POST'])]
    public function buscarCitasFacultativoCitaAdmin(
        Request $request,
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
                    array_push($arraynodisponible, $fechaarray);
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
        EntityManagerInterface $em,
    ) {
        // Recupero el Facultativo y el Paciente que me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);
        // Recupero Fecha Seleccionada de Cita
        $fechacita = $request->request->get('txFecha');
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

        // Transformo Fecha que llega a Datetime
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $fechacita);
        dump($diaconvertido);
        // Recupero citas disponibles de ese facultativo en esa fecha
        $citasdisponiblesfacultativo = $em
            ->getRepository(CitasDisponibles::class)
            ->findBy([
                'idfacultativo' => $idfacultativo,
                'fecha' => $diaconvertido,
                'disponible' => 'true',
            ]);

        // Recupero dia de la semana de la fecha seleccionada
        $diasemana = date('l', strtotime($fechacita));
        switch ($diasemana) {
            case 'Sunday':
                $diasemanaformat = 'DOMINGO';
                break;
            case 'Monday':
                $diasemanaformat = 'LUNES';
                break;
            case 'Tuesday':
                $diasemanaformat = 'MARTES';
                break;
            case 'Wednesday':
                $diasemanaformat = 'MIERCOLES';
                break;
            case 'Thursday':
                $diasemanaformat = 'JUEVES';
                break;
            case 'Friday':
                $diasemanaformat = 'VIERNES';
                break;
            case 'Saturday':
                $diasemanaformat = 'SABADO';
                break;
        }

        // Envio a la vista de Seleccion de Horas, Datos Facultativo-Especialidades, Datos Pacientes, Citas Disponibles
        return $this->render('citas/seleccionhoracitasAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPaciente' => $paciente,
            'fechaseleccionada' => $fechacita,
            'diadelasemana' => $diasemanaformat,
            'citasDisponibles' => $citasdisponiblesfacultativo,
        ]);
    }

    // Alta de Cita enviando el iddefacultativo, idpaciente, fecha y hora
    #[Route('/altacitasadmin', name: 'altaCitasAdmin', methods: ['GET', 'POST'])]
    public function altaCitasAdmin(
        Request $request,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);
        $horadisponible = $request->query->get('hora');
        dump($horadisponible);
        $fechaseleccionada = $request->query->get('fecha');
        dump($fechaseleccionada);
        // Convertimos fecha string a fecha DateTime
        $diaconvertido = \DateTime::createFromFormat(
            'Y-m-d',
            $fechaseleccionada
        );
        dump($diaconvertido);

        // Accedemos al objeto Facultativo para guardarlo con el registro de citas
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Accedemos al objeto Paciente para guardarlo con el registro de citas
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Creamos instancia de Citas
        $nuevacita = new Citas();
        // Añado campos
        $nuevacita->setFecha($diaconvertido);
        $nuevacita->setHora($horadisponible);
        // Añado el Facultativo
        $nuevacita->setIdfacultativo($facultativo);
        // Añado el Paciente
        $nuevacita->setIdpaciente($paciente);
        dump($nuevacita);

        // Inserto registro en la tabla de Citas
        $em->persist($nuevacita);
        $em->flush();

        // Actualizo cita disponible a no disponible
        $citadisponible = $em
            ->getRepository(CitasDisponibles::class)
            ->findOneBy([
                'idfacultativo' => $idfacultativo,
                'fecha' => $diaconvertido,
                'hora' => $horadisponible,
            ]);
        dump($citadisponible);
        // Modificamos el valor de disponible
        $citadisponible->setDisponible(false);
        dump($citadisponible);
        // Modificamos el Registro
        $em->persist($citadisponible);
        $em->flush();

        // Creo mensaje de Alta de Cita
        $mensaje =
            'Se ha creado cita para paciente ' .
            $idpaciente .
            ' con facultativo' .
            $idfacultativo;

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }

    // Alta de Cita enviando el iddefacultativo, idpaciente, fecha y hora
    #[Route('/historialcitasadmin', name: 'historialCitasAdmin', methods: ['GET', 'POST'])]
    public function historialCitasAdmin(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);
        $idpaciente = $request->query->get('idpaciente');
        dump($idpaciente);

        // Accedemos al objeto Facultativo para mandar values
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Accedemos al objeto Paciente para mandar values
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero la Fecha del dia para enviarla a Vista
        $fechadia = date('Y-m-d');

        // Recupero todas las citas del Paciente con Paginacion
        $query = $em
            ->getRepository(Citas::class)
            ->findBy(['idpaciente' => $idpaciente]);

        $datosCitasPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Envio a la vista de Historial de Citas, Datos Facultativo-Especialidades, Datos Pacientes, Citas Disponibles
        return $this->render('citas/historialcitasAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPaciente' => $paciente,
            'historialCitas' => $datosCitasPaginados,
            'fechadia' => $fechadia,
        ]);
    }

    // Alta de Cita enviando el iddefacultativo, idpaciente, fecha y hora
    #[Route('/bajacitaAdmin', name: 'bajaCitaAdmin', methods: ['GET', 'POST'])]
    public function bajaCitaAdmin(
        Request $request,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        //Inicializamos mensajes
        $mensaje = null;
        $mensajewarning = null;

        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idcita = $request->query->get('idcita');
        dump($idcita);

        // Recupero la cita a dar de baja del Paciente
        $citaborrar = $em->getRepository(Citas::class)->findOneBy([
            'idcita' => $idcita,
        ]);
        dump($citaborrar);
        // Recupero el idfacultativo y el idpaciente de la cita
        $idfacultativo = $citaborrar->getIdfacultativo();
        dump($idfacultativo);
        $idpaciente = $citaborrar->getIdpaciente();
        dump($idpaciente);

        // Si existe la cita se da de Baja
        if ($citaborrar) {
            // Realizamos Baja de la cita
            $em->remove($citaborrar);
            $em->flush();

            $mensaje = 'Se ha dado de baja la cita';
            // Si no existe la cita se manda mensaje
        } else {
            $mensajewarning =
                'No existe la cita ' . $idcita . ' que se quiere dar de baja';
        }

        // Accedemos al objeto Facultativo para mandar values
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Accedemos al objeto Paciente para mandar values
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recupero la Fecha del dia para enviarla a Vista
        $fechadia = date('Y-m-d');

        // Recupero todas las citas del Paciente con Paginacion
        $query = $em
            ->getRepository(Citas::class)
            ->findBy(['idpaciente' => $idpaciente]);

        $datosCitasPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Envio a la vista de Historial de Citas, Datos Facultativo-Especialidades, Datos Pacientes, Citas Disponibles
        return $this->render('citas/historialcitasAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosPaciente' => $paciente,
            'historialCitas' => $datosCitasPaginados,
            'fechadia' => $fechadia,
            'mensaje' => $mensaje,
            'mensajewarning' => $mensajewarning,
        ]);
    }

    // Imprimir Justificante de Cita
    #[Route('/imprimirjustificanteAdmin', name: 'imprimirJustificanteAdmin', methods: ['GET', 'POST'])]
    public function imprimirJustificanteAdmin(
        Request $request,
        EntityManagerInterface $em,
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idcita = $request->query->get('idcita');
        dump($idcita);

        // Recupero la cita a imprimir
        $cita = $em->getRepository(Citas::class)->findOneBy([
            'idcita' => $idcita,
        ]);
        dump($cita);

        // Recupero el Id del Paciente de la cita
        $idpaciente = $cita->getIdpaciente();
        dump($idpaciente);
        // Recupero el Id del Facultativo de la cita
        $idfacultativo = $cita->getIdfacultativo();
        dump($idfacultativo);

        // Recuperamos datos del Paciente de esa cita
        $paciente = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);
        dump($paciente);

        // Recuperamos datos del Facultativo de esa cita
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);
        // Recupero la Especialidad del Facultativo
        $idespecialidad = $facultativo->getEspecialidad();
        dump($idespecialidad);
        // Recuperamos nombre especialidad Facultativo
        $especialidad = $em
            ->getRepository(Especialidades::class)
            ->findOneByIdespecialidad($idespecialidad);
        dump($especialidad);
        // Recupero Nombre de la Especialidad
        $nombreespecialidad = $especialidad->getEspecialidad();
        dump($nombreespecialidad);

        // Recupero la Fecha del dia para enviarla a Vista
        $fechadia = date('Y-m-d');

        // Preparamos la página HTML para generar PDF generado un objeto renderview
        $html = $this->renderView('citas/JustificantePDF.html.twig', 
        [
            'datosCita'  => $cita,
            'datosPaciente'  => $paciente,
            'datosFacultativo'  => $facultativo,
            'datosEspecialidad'  => $nombreespecialidad,
            'fechadia' => $fechadia
        ]);

        // Asignamos opciones de PDF
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');
        // Crea una instancia de Dompdf con nuestras opciones
        $dompdf = new Dompdf($pdfOptions);

        // Cargamos la página HTML en Dompdf        
        // $html .= '<link type="text/css" href="/public/bootstrap/css/bootstrap.min.css" rel="stylesheet" />';
        $dompdf->loadHtml($html);

        // También podemos de forma opcional indicar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'portrait');
 
        // Renderiza el HTML como PDF
        $dompdf->render();

        // Podemos generar el pdf y visualizarlo en el navegador si modificamos la propiedad Attachment al valor false.
        $dompdf->stream("JustificanteCita.pdf", [
            "Attachment" => false
        ]);
    }

    //****************************************************************
    // Alta/Modificacion de Citas de Paciente por parte del Paciente *
    //****************************************************************
    #[Route('/buscarfacultativo', name: 'buscarCitasFacultativoCita', methods: ['GET', 'POST'])]
    public function buscarCitasFacultativoCita(
        Request $request,
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
    #[Route('/buscarfacultativoApellido', name: 'buscarFacultativoCitaApellido', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitaApellido(
        Request $request,
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
    #[Route('/buscarfacultativoTelefono', name: 'buscarFacultativoCitaTelefono', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitaTelefono(
        Request $request,
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
}
