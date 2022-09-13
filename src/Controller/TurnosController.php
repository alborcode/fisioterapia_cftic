<?php
namespace App\Controller;

use App\Entity\Turnos;
use App\Form\TurnosType;
use App\Repository\TurnosRepository;
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

use App\Security\EmailVerifier;
use App\Security\FisioterapiaAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

#[Route('/turnos')]
class TurnosController extends AbstractController
{
    //*************************************************************************
    // Alta/Modificacion de Turno de Facultativo por parte del Administrativo *
    //*************************************************************************
    #[Route('/buscarturnosfacultativo', name: 'buscarTurnosFacultativo', methods: ['GET', 'POST'])]
    public function buscarTurnosFacultativo(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Si no se relleno se recuperan todos los Facultativos con Paginacion
        $query = $em->getRepository(Facultativos::class)->findAll();

        $datosFacultativosPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Se envia a pagina enviando los datos de los facultativos
        return $this->render('turnos/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Apellido
    #[Route('/buscarturnosfacultativoApellido', name: 'buscarTurnosFacultativoApellido', methods: ['GET', 'POST'])]
    public function buscarTurnosFacultativoApellido(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        $busquedaapellido = $request->query->get('txtApellido');
        dump($busquedaapellido);

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            // Se recuperan todos los Facultativos con Paginacion
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
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
            $query = $em->getRepository(Facultativos::class)->findAll();

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        return $this->render('turnos/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $facultativos,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Telefono
    #[Route('/buscarturnosfacultativoTelefono', name: 'buscarTurnosFacultativoTelefono', methods: ['GET', 'POST'])]
    public function buscarTurnosFacultativoTelefono(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        $busquedatelefono = $request->query->get('txtTelefono');

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Se recuperan todos los Facultativos con Paginacion
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
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
            $query = $em->getRepository(Facultativos::class)->findAll();

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('turnos/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Formulario para mostrar Turnos si existen Datos de Facultativos y Formulario para añadir/modificar
    #[Route('/mostrarturnosfacultativo', name: 'mostrarTurnosFacultativoAdmin', methods: ['GET', 'POST'])]
    public function mostrarTurnosFacultativoAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Facultativo que me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero datos de turnos de facultativo para enviar los Values a Formulario
        $turnosfacultativo = $em
            ->getRepository(Turnos::class)
            ->findByIdfacultativo($idfacultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Datos Turnos Facultativo y Datos de Facultativo
        return $this->render('turnos/modificarTurnosFacultativo.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosTurnos' => $turnosfacultativo,
        ]);
    }

    // Recogemos Datos Formulario para modificar Turnos si ya existen o Darlos de Alta si no existen
    #[Route('/modificarfacultativo', name: 'modificarFacultativoTurnosAdmin', methods: ['GET', 'POST'])]
    public function modificarFacultativoAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        TurnosRepository $turnosRepository,
        EntityManagerInterface $em
    ) {
        $mensaje = null;
        $mensajewarning = null;

        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');

        // Recogemos datos de formulario con Post de cada uno de los turnos de los dias de la semana
        $lunesseleccionado = $request->request->get('txtLunes');
        $horainiciolunes = $request->request->get('txtHorainiciolunes');
        $horafinlunes = $request->request->get('txtHorafinlunes');

        $martesseleccionado = $request->request->get('txtMartes');
        $horainiciomartes = $request->request->get('txtHorainiciomartes');
        $horafinmartes = $request->request->get('txtHorafinmartes');

        $miercolesseleccionado = $request->request->get('txtMiercoles');
        $horainiciomiercoles = $request->request->get('txtHorainiciomiercoles');
        $horafinmiercoles = $request->request->get('txtHorafinmiercoles');

        $juevesseleccionado = $request->request->get('txtJueves');
        $horainiciojueves = $request->request->get('txtHorainiciojueves');
        $horafinjueves = $request->request->get('txtHorafinjueves');

        $viernesseleccionado = $request->request->get('txtViernes');
        $horainicioviernes = $request->request->get('txtHorainicioviernes');
        $horafinviernes = $request->request->get('txtHorafinviernes');

        $horainicio = null;
        $horafin = null;

        // Accedemos al objeto Facultativo para guardarlo por cada uno de los registros de turno
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Si el dia esta seleccionado
        if ($lunesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (LUNES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'LUNES',
            ]);
            // Si existen los Turnos del facultativo de cada dia se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Lunes
                $turnosfacultativo->setDiasemana('LUNES');
                $turnosfacultativo->setHorainicio($horainiciolunes);
                $turnosfacultativo->setHorafin($horafinlunes);
                $turnosfacultativo->setIdfacultativo($facultativo);

                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensajelunes =
                    'Se ha modificado el Turno del Lunes' .
                    ' para el facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Lunes
                $nuevoturno->setDiasemana('LUNES');
                $nuevoturno->setHorainicio($horainiciolunes);
                $nuevoturno->setHorafin($horafinlunes);
                $nuevoturno->setIdfacultativo($facultativo);

                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
            }
        }

        if ($martesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (MARTES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'MARTES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Martes
                $turnosfacultativo->setDiasemana('MARTES');
                $turnosfacultativo->setHorainicio($horainiciomartes);
                $turnosfacultativo->setHorafin($horafinmartes);
                $turnosfacultativo->setIdfacultativo($facultativo);

                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Martes
                $nuevoturno->setDiasemana('MARTES');
                $nuevoturno->setHorainicio($horainiciomartes);
                $nuevoturno->setHorafin($horafinmartes);
                $nuevoturno->setIdfacultativo($facultativo);

                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
            }
        }

        if ($miercolesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (MIERCOLES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'MIERCOLES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Miercoles
                $turnosfacultativo->setDiasemana('MIERCOLES');
                $turnosfacultativo->setHorainicio($horainiciomiercoles);
                $turnosfacultativo->setHorafin($horafinmiercoles);
                $turnosfacultativo->setIdfacultativo($facultativo);

                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Miercoles
                $nuevoturno->setDiasemana('MIERCOLES');
                $nuevoturno->setHorainicio($horainiciomiercoles);
                $nuevoturno->setHorafin($horafinmiercoles);
                $nuevoturno->setIdfacultativo($facultativo);

                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
            }
        }

        if ($juevesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (JUEVES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'JUEVES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Jueves
                $turnosfacultativo->setDiasemana('JUEVES');
                $turnosfacultativo->setHorainicio($horainiciojueves);
                $turnosfacultativo->setHorafin($horafinjueves);
                $turnosfacultativo->setIdfacultativo($facultativo);

                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Jueves
                $nuevoturno->setDiasemana('JUEVES');
                $nuevoturno->setHorainicio($horainiciojueves);
                $nuevoturno->setHorafin($horafinjueves);
                $nuevoturno->setIdfacultativo($facultativo);

                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
            }
        }

        if ($viernesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (VIERNES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => $idfacultativo,
                'diasemana' => 'VIERNES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Viernes
                $turnosfacultativo->setDiasemana('VIERNES');
                $turnosfacultativo->setHorainicio($horainicioviernes);
                $turnosfacultativo->setHorafin($horafinviernes);
                $turnosfacultativo->setIdfacultativo($facultativo);

                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Viernes
                $nuevoturno->setDiasemana('VIERNES');
                $nuevoturno->setHorainicio($horainicioviernes);
                $nuevoturno->setHorafin($horafinviernes);
                $nuevoturno->setIdfacultativo($facultativo);

                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensaje =
                    'Se ha dado de alta los Turno del ' .
                    ' facultativo ' .
                    $idfacultativo .
                    'Vuelva a dar de alta las Citas Disponibles a partir de los turnos actuales';
            }
        }

        // Devuelvo control a Pagina Inicio de Administrador mandando mensajes por cada dia
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' =>
                'Se ha dado de alta los Turnos del ' .
                ' facultativo ' .
                $idfacultativo,
        ]);
    }
}
