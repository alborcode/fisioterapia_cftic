<?php
namespace App\Controller;

use App\Entity\Citas;
use App\Form\CitasType;
use App\Repository\CitasRepository;
use App\Entity\CitasDisponibles;
use App\Repository\CitasDisponiblesRepository;
use App\Entity\Facultativos;
use App\Form\FacultativosType;
use App\Repository\FacultativosRepository;
use App\Entity\Especialidades;
use App\Repository\EspecialidadesRepository;
use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use App\Entity\Turnos;
use App\Form\TurnosType;
use App\Repository\TurnosRepository;
use App\Entity\Vacaciones;
use App\Form\VacacionesType;
use App\Repository\VacacionesRepository;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

#[Route('/citasdisponibles')]
class CitasDisponiblesController extends AbstractController
{
    //****************************************************************
    // Alta de Citas Disponibles de Facultativo segun Turnos de Alta *
    //****************************************************************
    #[Route('/buscarfacultativocitasd', name: 'buscarFacultativoCitasD', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitasD(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero datos de Facultativos con Paginacion
        $query = $em->getRepository(Facultativos::class)->findAll();
        $datosFacultativosPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Se envia a pagina enviando los datos de los facultativos
        return $this->render(
            'citasdisponibles/busquedacitasfacultativo.html.twig',
            [
                'datosFacultativos' => $datosFacultativosPaginados,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Buscar Turnos Facultativo por Apellido
    #[Route('/buscarfacultativocitasDapellido', name: 'buscarFacultativoCitasDApellido', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitasDApellido(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        $busquedaapellido = $request->query->get('txtApellido');
        dump($busquedaapellido);

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            // Recupero datos de Facultativos con Paginacion
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.apellido1 like :parametro'
            );
            // Concateno la variable a buscar y el % del Like
            $query->setParameter('parametro', $busquedaapellido . '%');

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Si no se relleno recupero datos de Facultativos con Paginacion
            $query = $em->getRepository(Facultativos::class)->findAll();

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        return $this->render(
            'citasdisponibles/busquedacitasfacultativo.html.twig',
            [
                'datosFacultativos' => $datosFacultativosPaginados,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Buscar Turnos Facultativo por Telefono
    #[Route('/buscarfacultativocitasDtelefono', name: 'buscarFacultativoCitasDTelefono', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitasDTelefono(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        $busquedatelefono = $request->query->get('txtTelefono');

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Recupero datos de Facultativos con Paginacion
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Si no se relleno recupero datos de Facultativos con Paginacion
            $query = $em->getRepository(Facultativos::class)->findAll();

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
        return $this->render(
            'citasdisponibles/busquedacitasfacultativo.html.twig',
            [
                'datosFacultativos' => $datosFacultativosPaginados,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Formulario para mostrar Citas Disponibles si existen Datos de Facultativos y Formulario para añadir/modificar
    #[Route('/mostrarcitasdisponiblesfacultativo', name: 'mostrarCitasDisponiblesFacultativo', methods: ['GET', 'POST'])]
    public function mostrarCitasDisponiblesFacultativo(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero el Facultativo que me llega
        $idfacultativo = $request->query->get('idfacultativo');

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero datos de citas disponibles para enviar los Values a Formulario con Paginacion
        $query = $em
            ->getRepository(CitasDisponibles::class)
            ->findByIdfacultativo($idfacultativo);

        $datosCitasPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            10 // Número de elementos por página
        );

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Datos Citas Disponibles y Datos de Facultativo y Especialidades
        return $this->render(
            'citasdisponibles/altacitasDfacultativo.html.twig',
            [
                'datosFacultativo' => $facultativo,
                'datosEspecialidades' => $especialidades,
                // 'datosCitasDisponibles' => $citasdisponibles,
                'datosCitasDisponibles' => $datosCitasPaginados,
            ]
        );
    }

    // Recogemos Datos Formulario para dar de alta Citas Disponibles de Facultativo
    #[Route('/altacitasDfacultativo', name: 'altaCitasDFacultativo', methods: ['GET', 'POST'])]
    public function modificarFacultativoAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        TurnosRepository $turnosRepository,
        EntityManagerInterface $em
    ) {
        $mensaje = null;
        $mensajewarning = null;

        // Recupero el Facultativo que me llega
        $idfacultativo = $request->query->get('idfacultativo');

        // Recupero datos de facultativo
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Buscamos si existen citas disponibles
        $citasdisponiblesfacultativo = $em
            ->getRepository(CitasDisponibles::class)
            ->findByIdfacultativo($idfacultativo);

        // Si existen se borran antes de dar de Alta
        if ($citasdisponiblesfacultativo) {
            // Recupero todas las citas disponibles (Recupera Array)
            $citasdisponibles = $em
                ->getRepository(CitasDisponibles::class)
                ->findAll();

            // Realizo borrado de las citas disponibles
            $query = $em->createQuery(
                'DELETE FROM App\Entity\CitasDisponibles c WHERE c.idfacultativo =:parametro'
            );
            // Defino el parametro
            $query->setParameter('parametro', $idfacultativo);
            $datos = $query->getResult();
        }

        // Fijamos Fechas de Inicio y de Fin
        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');
        // Sumo un año a la Fecha del dia para fijar fecha de fin
        //$fechafin = date('Y-m-d', strtotime($fechadia . '+ 1 year'));
        // Recuperar Año actual
        $anio = date('Y');
        $fechafin = $anio . '-12' . '-31';
        dump($fechadia);
        dump($fechafin);

        // Inicializo variables a usar en bucles
        $horainicio = null;
        $horafin = null;
        $existelunes = false;
        $existemartes = false;
        $existemiercoles = false;
        $existejueves = false;
        $existeviernes = false;

        // Recupero turnos de cada dia para ese facultativo y las horas de inicio y de fin si existe turno
        $turnoslunesfacultativo = $em->getRepository(Turnos::class)->findOneBy([
            'idfacultativo' => $idfacultativo,
            'diasemana' => 'LUNES',
        ]);
        dump($turnoslunesfacultativo);
        if (!$turnoslunesfacultativo) {
            $horainiciolunes = null;
            $horafinlunes = null;
            $existelunes = false;
        } else {
            $horainiciolunes = $turnoslunesfacultativo->getHorainicio();
            $horafinlunes = $turnoslunesfacultativo->getHorafin();
            $existelunes = true;
        }

        $turnosmartesfacultativo = $em
            ->getRepository(Turnos::class)
            ->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'MARTES',
            ]);

        if (!$turnosmartesfacultativo) {
            $horainiciomartes = null;
            $horafinmartes = null;
            $existemartes = false;
        } else {
            $horainiciomartes = $turnosmartesfacultativo->getHorainicio();
            $horafinmartes = $turnosmartesfacultativo->getHorafin();
            $existemartes = true;
        }

        $turnosmiercolesfacultativo = $em
            ->getRepository(Turnos::class)
            ->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'MIERCOLES',
            ]);

        if (!$turnosmiercolesfacultativo) {
            $horainiciomiercoles = null;
            $horafinmiercoles = null;
            $existemiercoles = false;
        } else {
            $horainiciomiercoles = $turnosmiercolesfacultativo->getHorainicio();
            $horafinmiercoles = $turnosmiercolesfacultativo->getHorafin();
            $existemiercoles = true;
        }

        $turnosjuevesfacultativo = $em
            ->getRepository(Turnos::class)
            ->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'JUEVES',
            ]);

        if (!$turnosjuevesfacultativo) {
            $horainiciojueves = null;
            $horafinjueves = null;
            $existejueves = false;
        } else {
            $horainiciojueves = $turnosjuevesfacultativo->getHorainicio();
            $horafinjueves = $turnosjuevesfacultativo->getHorafin();
            $existejueves = true;
        }

        $turnosviernesacultativo = $em
            ->getRepository(Turnos::class)
            ->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'VIERNES',
            ]);

        if (!$turnosviernesacultativo) {
            $horainicioviernes = null;
            $horafinviernes = null;
            $existeviernes = false;
        } else {
            $horainicioviernes = $turnosviernesacultativo->getHorainicio();
            $horafinviernes = $turnosviernesacultativo->getHorafin();
            $existeviernes = true;
        }

        // Bucle para añadir Generacion Citas Disponibles segun los Turnos del facultativo
        $fechaactual = $fechadia;
        $fechafintramo = $fechafin;
        while ($fechaactual <= $fechafintramo):
            // Recupero dia de la semana de fecha
            $diasemana = date('l', strtotime($fechaactual));
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

            // Si es Lunes y Existe Lunes en los turnos
            if ($diasemanaformat == 'LUNES' and ($existelunes = true)) {
                $horaactual = $horainiciolunes;
                $horafintramo = $horafinlunes;

                // Bucle para añadir horas del turno en citas disponibles. Por cada hora añado la cita disponible
                while ($horaactual < $horafintramo):
                    // Declaro variable de clase entidad CitaDisponobles
                    $citasdisponiblesfacultativo = new CitasDisponibles();
                    // Añado valores a cada uno de los campos para el registro del Jueves
                    $diaconvertido = \DateTime::createFromFormat(
                        'Y-m-d',
                        $fechaactual
                    );

                    $citasdisponiblesfacultativo->setFecha($diaconvertido);
                    $citasdisponiblesfacultativo->setDisponible('true');
                    $citasdisponiblesfacultativo->setIdfacultativo(
                        $facultativo
                    );
                    $citasdisponiblesfacultativo->setHora($horaactual);
                    // Inserto Cita Disponible
                    $em->persist($citasdisponiblesfacultativo);
                    $em->flush();

                    // Sumo una hora a la hora del bucle a tratar
                    //$horainiciolunes->modify('+ 1 hour');
                    $horaactual = $horaactual + 1;
                endwhile;
            }

            // Si es Martes y Existe Martes en los turnos
            if ($diasemanaformat == 'MARTES' and ($existemartes = true)) {
                $horaactual = $horainiciomartes;
                $horafintramo = $horafinmartes;

                // Bucle para añadir horas del turno en citas disponibles. Por cada hora añado la cita disponible
                while ($horaactual < $horafintramo):
                    // Declaro variable de clase entidad CitaDisponobles
                    $citasdisponiblesfacultativo = new CitasDisponibles();
                    // Añado valores a cada uno de los campos para el registro del Jueves
                    $diaconvertido = \DateTime::createFromFormat(
                        'Y-m-d',
                        $fechaactual
                    );

                    $citasdisponiblesfacultativo->setFecha($diaconvertido);
                    $citasdisponiblesfacultativo->setDisponible('true');
                    $citasdisponiblesfacultativo->setIdfacultativo(
                        $facultativo
                    );
                    $citasdisponiblesfacultativo->setHora($horaactual);
                    // Inserto Cita Disponible
                    $em->persist($citasdisponiblesfacultativo);
                    $em->flush();

                    // Sumo una hora a la hora del bucle a tratar
                    //$horainiciolunes->modify('+ 1 hour');
                    $horaactual = $horaactual + 1;
                    dump($horaactual);
                endwhile;
            }

            // Si es Lunes y Existe Lunes en los turnos
            if ($diasemanaformat == 'MIERCOLES' and ($existemiercoles = true)) {
                $horaactual = $horainiciomiercoles;
                $horafintramo = $horafinmiercoles;

                // Bucle para añadir horas del turno en citas disponibles
                while ($horaactual < $horafintramo):
                    // Por cada hora añado la cita disponible
                    // Declaro variable de clase entidad CitaDisponobles
                    $citasdisponiblesfacultativo = new CitasDisponibles();
                    // Añado valores a cada uno de los campos para el registro del Jueves
                    $diaconvertido = \DateTime::createFromFormat(
                        'Y-m-d',
                        $fechaactual
                    );

                    $citasdisponiblesfacultativo->setFecha($diaconvertido);
                    $citasdisponiblesfacultativo->setDisponible('true');
                    $citasdisponiblesfacultativo->setIdfacultativo(
                        $facultativo
                    );
                    $citasdisponiblesfacultativo->setHora($horaactual);
                    // Inserto Cita Disponible
                    $em->persist($citasdisponiblesfacultativo);
                    $em->flush();

                    // Sumo una hora a la hora del bucle a tratar
                    //$horainiciolunes->modify('+ 1 hour');
                    $horaactual = $horaactual + 1;
                endwhile;
            }

            // Si es Jueves y Existe Jueves en los turnos
            if ($diasemanaformat == 'JUEVES' and ($existejueves = true)) {
                $horaactual = $horainiciojueves;
                $horafintramo = $horafinjueves;

                // Bucle para añadir horas del turno en citas disponibles
                while ($horaactual < $horafintramo):
                    // Por cada hora añado la cita disponible
                    // Declaro variable de clase entidad CitaDisponobles
                    $citasdisponiblesfacultativo = new CitasDisponibles();
                    // Añado valores a cada uno de los campos para el registro del Jueves
                    $diaconvertido = \DateTime::createFromFormat(
                        'Y-m-d',
                        $fechaactual
                    );

                    $citasdisponiblesfacultativo->setFecha($diaconvertido);
                    $citasdisponiblesfacultativo->setDisponible('true');
                    $citasdisponiblesfacultativo->setIdfacultativo(
                        $facultativo
                    );
                    $citasdisponiblesfacultativo->setHora($horaactual);
                    // Inserto Cita Disponible
                    $em->persist($citasdisponiblesfacultativo);
                    $em->flush();

                    // Sumo una hora a la hora del bucle a tratar
                    //$horainiciolunes->modify('+ 1 hour');
                    $horaactual = $horaactual + 1;
                endwhile;
            }

            // Si es Virenes y Existe Viernes en los turnos
            if ($diasemanaformat == 'VIERNES' and ($existeviernes = true)) {
                $horaactual = $horainicioviernes;
                $horafintramo = $horafinviernes;

                // Bucle para añadir horas del turno en citas disponibles
                while ($horaactual < $horafintramo):
                    // Por cada hora añado la cita disponible
                    // Declaro variable de clase entidad CitaDisponobles
                    $citasdisponiblesfacultativo = new CitasDisponibles();
                    // Añado valores a cada uno de los campos para el registro del Jueves
                    $diaconvertido = \DateTime::createFromFormat(
                        'Y-m-d',
                        $fechaactual
                    );

                    $citasdisponiblesfacultativo->setFecha($diaconvertido);
                    $citasdisponiblesfacultativo->setDisponible('true');
                    $citasdisponiblesfacultativo->setIdfacultativo(
                        $facultativo
                    );
                    $citasdisponiblesfacultativo->setHora($horaactual);
                    // Inserto Cita Disponible
                    $em->persist($citasdisponiblesfacultativo);
                    $em->flush();

                    // Sumo una hora a la hora del bucle a tratar
                    //$horainiciolunes->modify('+ 1 hour');
                    $horaactual = $horaactual + 1;
                endwhile;
            }

            // Sumo un dia a la Fecha del bucle
            //$fechaactual->modify('+ 1 days');
            $fechaactual = date('Y-m-d', strtotime($fechaactual . '+ 1 days'));
        endwhile;

        // Devuelvo control a Pagina Inicio de Administrador mandando mensajes por cada dia
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' =>
                'Se ha dado de alta las citas Disponibles según turno del ' .
                ' facultativo ' .
                $idfacultativo,
        ]);
    }
}
