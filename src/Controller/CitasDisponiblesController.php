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
        EntityManagerInterface $em
    ) {
        // $em = $this->getDoctrine()->getManager();
        // $query = $em->getRepository(Pacientes::class)>findAll();

        // $pagination = $paginator->paginate(
        //     $query /* query NOT result */,
        //     $request->query->getInt('page', 1) /*page number*/,
        //     10 /*limit per page*/
        // );

        // // parameters to template
        // return $this->render('pacientes/mostrarPerfil.html.twig', [
        //     'datosPacientes' => $pagination,
        // ]);

        // Recupero todos los Facultativos
        $facultativos = $em->getRepository(Facultativos::class)->findAll();
        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Se envia a pagina enviando los datos de los facultativos
        return $this->render(
            'citasdisponibles/busquedacitasfacultativo.html.twig',
            [
                'datosFacultativos' => $facultativos,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Buscar Turnos Facultativo por Apellido
    #[Route('/buscarfacultativocitasDapellido', name: 'buscarFacultativoCitasDApellido', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitasDApellido(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // $em = $this->getDoctrine()->getManager();
        // $query = $em->getRepository(Pacientes::class)>findAll();

        // $pagination = $paginator->paginate(
        //     $query /* query NOT result */,
        //     $request->query->getInt('page', 1) /*page number*/,
        //     10 /*limit per page*/
        // );

        // // parameters to template
        // return $this->render('pacientes/mostrarPerfil.html.twig', [
        //     'datosPacientes' => $pagination,
        // ]);

        // Recogemos datos de formulario con Get dado que es una busqueda
        // $busquedaapellido = $request->request->get('txtApellido');
        $busquedaapellido = $request->query->get('txtApellido');
        dump($busquedaapellido);

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.apellido1 like :parametro'
            );
            // Concateno la variable a buscar y el % del Like
            $query->setParameter('parametro', $busquedaapellido . '%');
            dump($query);
            // Al hacer el getresult ejecuta la Query y obtiene los resultados
            $facultativos = $query->getResult();
            dump($facultativos);
        } else {
            // Si no se relleno se recuperan todos los Pacientes
            $facultativos = $em->getRepository(Facultativos::class)->findAll();
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        return $this->render(
            'citasdisponibles/busquedacitasfacultativo.html.twig',
            [
                'datosFacultativos' => $facultativos,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Buscar Turnos Facultativo por Telefono
    #[Route('/buscarfacultativocitasDtelefono', name: 'buscarFacultativoCitasDTelefono', methods: ['GET', 'POST'])]
    public function buscarFacultativoCitasDTelefono(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // $em = $this->getDoctrine()->getManager();
        // $query = $em->getRepository(Pacientes::class)>findAll();

        // $pagination = $paginator->paginate(
        //     $query /* query NOT result */,
        //     $request->query->getInt('page', 1) /*page number*/,
        //     10 /*limit per page*/
        // );

        // // parameters to template
        // return $this->render('pacientes/mostrarPerfil.html.twig', [
        //     'datosPacientes' => $pagination,
        // ]);

        /// Recogemos datos de formulario con Get dado que es una busqueda
        //$busquedatelefono = $request->request->get('txtTelefono');
        $busquedatelefono = $request->query->get('txtTelefono');
        dump($busquedatelefono);

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Select de Pacientes con Where mandado por parametro
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);
            // Al hacer el getresult ejecuta la Query y obtiene los resultados
            $facultativos = $query->getResult();
            dump($facultativos);
        } else {
            // Si no se relleno se recuperan todos los Pacientes
            $facultativos = $em->getRepository(Facultativos::class)->findAll();
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render(
            'citasdisponibles/busquedacitasfacultativo.html.twig',
            [
                'datosFacultativos' => $facultativos,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Formulario para mostrar Turnos si existen Datos de Facultativos y Formulario para añadir/modificar
    #[Route('/mostrarcitasdisponiblesfacultativo', name: 'mostrarCitasDisponiblesFacultativo', methods: ['GET', 'POST'])]
    public function mostrarCitasDisponiblesFacultativo(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
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

        // Recupero datos de turnos de facultativo para enviar los Values a Formulario
        $citasdisponibles = $em
            ->getRepository(CitasDisponibles::class)
            ->findByIdfacultativo($idfacultativo);
        dump($citasdisponibles);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Envio a la vista de Datos Turnos Facultativo y Datos de Facultativo
        return $this->render(
            'citasdisponibles/altacitasDfacultativo.html.twig',
            [
                'datosFacultativo' => $facultativo,
                'datosEspecialidades' => $especialidades,
                'datosCitasDisponibles' => $citasdisponibles,
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
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recupero datos de facultativo
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Buscamos si existen citas disponibles
        $citasdisponiblesfacultativo = $em
            ->getRepository(CitasDisponibles::class)
            ->findByIdfacultativo($idfacultativo);
        dump($citasdisponiblesfacultativo);

        // Si existen se borran antes de dar de Alta
        if ($citasdisponiblesfacultativo) {
            // Recupero todas las citas disponibles (Recupera Array)
            $citasdisponibles = $em
                ->getRepository(CitasDisponibles::class)
                ->findAll();
            dump($citasdisponibles);

            // Realizo borrado de las citas disponibles
            $query = $em->createQuery(
                'DELETE FROM App\Entity\CitasDisponibles c WHERE c.idfacultativo =:parametro'
            );
            // Defino el parametro
            $query->setParameter('parametro', $idfacultativo);
            dump($query);
            $datos = $query->getResult();
            dump($datos);
        }

        // Fijamos Fechas de Inicio y de Fin
        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');
        // Sumo un mes a la Fecha del dia para fijar fecha de fin
        $fechafin = date('Y-m-d', strtotime($fechadia . '+ 1 month'));
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
        dump($turnosmartesfacultativo);
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
        dump($turnosmiercolesfacultativo);
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
        dump($turnosjuevesfacultativo);
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
        dump($turnosviernesacultativo);
        if (!$turnosviernesacultativo) {
            $horainicioviernes = null;
            $horafinviernes = null;
            $existeviernes = false;
        } else {
            $horainicioviernes = $turnosviernesacultativo->getHorainicio();
            $horafinviernes = $turnosviernesacultativo->getHorafin();
            $existeviernes = true;
            dump($horainicio);
            dump($horafin);
        }

        // Bucle para añadir Generacion Citas Disponibles segun los Turnos del facultativo
        $fechaactual = $fechadia;
        dump($fechaactual);
        $fechafintramo = $fechafin;
        dump($fechafintramo);
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
            dump($diasemanaformat);

            // Si es Lunes y Existe Lunes en los turnos
            if ($diasemanaformat == 'LUNES' and ($existelunes = true)) {
                $horaactual = $horainiciolunes;
                $horafintramo = $horafinlunes;
                dump($horaactual);
                dump($horafintramo);

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
                    dump($diaconvertido);
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

            // Si es Martes y Existe Martes en los turnos
            if ($diasemanaformat == 'MARTES' and ($existemartes = true)) {
                $horaactual = $horainiciomartes;
                $horafintramo = $horafinmartes;
                dump($horaactual);
                dump($horafintramo);
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
                    dump($diaconvertido);
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
                dump($horaactual);
                dump($horafintramo);
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
                    dump($diaconvertido);
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

            // Si es Jueves y Existe Jueves en los turnos
            if ($diasemanaformat == 'JUEVES' and ($existejueves = true)) {
                $horaactual = $horainiciojueves;
                $horafintramo = $horafinjueves;
                dump($horaactual);
                dump($horafintramo);
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
                    dump($diaconvertido);
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

            // Si es Virenes y Existe Viernes en los turnos
            if ($diasemanaformat == 'VIERNES' and ($existeviernes = true)) {
                $horaactual = $horainicioviernes;
                $horafintramo = $horafinviernes;
                dump($horaactual);
                dump($horafintramo);
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
                    dump($diaconvertido);
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

            // Sumo un dia a la Fecha del bucle
            $fechaactual = date('Y-m-d', strtotime($fechaactual . '+ 1 days'));
            //$fechaactual->modify('+ 1 days');
            dump($fechaactual);
            dump($fechafin);
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
