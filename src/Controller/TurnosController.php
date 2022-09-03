<?php
namespace App\Controller;

use App\Entity\Turnos;
use App\Form\TurnosType;
use App\Repository\TurnosRepository;
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
        return $this->render('turnos/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $facultativos,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Apellido
    #[Route('/buscarturnosfacultativoApellido', name: 'buscarTurnosFacultativoApellido', methods: ['GET', 'POST'])]
    public function buscarTurnosFacultativoApellido(
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
        return $this->render('turnos/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $facultativos,
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
        dump($idfacultativo);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero datos de turnos de facultativo para enviar los Values a Formulario
        $turnosfacultativo = $em
            ->getRepository(Turnos::class)
            ->findByIdfacultativo($idfacultativo);
        dump($turnosfacultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

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
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recogemos datos de formulario con Post de cada uno de los turnos de los dias de la semana
        $lunesseleccionado = $request->request->get('txtLunes');
        dump($lunesseleccionado);
        $horainiciolunes = $request->request->get('txtHorainiciolunes');
        $horafinlunes = $request->request->get('txtHorafinlunes');
        dump(' De ' . $horainiciolunes . ' a ' . $horafinlunes);

        $martesseleccionado = $request->request->get('txtMartes');
        dump($martesseleccionado);
        $horainiciomartes = $request->request->get('txtHorainiciomartes');
        $horafinmartes = $request->request->get('txtHorafinmartes');
        dump(' De ' . $horainiciomartes . ' a ' . $horafinmartes);

        $miercolesseleccionado = $request->request->get('txtMiercoles');
        dump($miercolesseleccionado);
        $horainiciomiercoles = $request->request->get('txtHorainiciomiercoles');
        $horafinmiercoles = $request->request->get('txtHorafinmiercoles');
        dump(' De ' . $horainiciomiercoles . ' a ' . $horafinmiercoles);

        $juevesseleccionado = $request->request->get('txtJueves');
        dump($juevesseleccionado);
        $horainiciojueves = $request->request->get('txtHorainiciojueves');
        $horafinjueves = $request->request->get('txtHorafinjueves');
        dump(' De ' . $horainiciojueves . ' a ' . $horafinjueves);

        $viernesseleccionado = $request->request->get('txtViernes');
        dump($viernesseleccionado);
        $horainicioviernes = $request->request->get('txtHorainicioviernes');
        $horafinviernes = $request->request->get('txtHorafinviernes');
        dump(' De ' . $horainicioviernes . ' a ' . $horafinviernes);

        // Accedemos al objeto Facultativo para guardarlo por cada uno de los registros de turno
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // $userinfo->setBirthday(new \DateTime($norm_date));
        // $userinfo->setBirthday(new \DateTime('now'));
        // $userinfo->setBirthday(new \DateTime('2013-01-15'));
        // $userinfo->setBirthday(new \DateTime('+2 days'), new \DateTimeZone('UCT'));
        // $lastmonth = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        // $today = date("H:i:s");                         // 17:16:18
        // $today = date("Y-m-d H:i:s");                   // 2001-03-10 17:16:18 (the MySQL DATETIME format)

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
                dump('despues de setDiasemana');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciolunes
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorainicio($horaconvertida);
                dump('despues de setHorainicio');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinlunes
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorafin($horaconvertida);
                dump('despues de setHorafin');
                $turnosfacultativo->setIdfacultativo($facultativo);
                dump('despues de setIdfacultativo');
                dump($turnosfacultativo);
                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensajelunes =
                    'Se ha modificado el Turno del Lunes' .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Lunes
                $nuevoturno->setDiasemana('LUNES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciolunes
                );
                dump($horaconvertida);
                $nuevoturno->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinlunes
                );
                dump($horaconvertida);
                $nuevoturno->setHorafin($horaconvertida);
                $nuevoturno->setIdfacultativo($facultativo);
                dump($nuevoturno);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensajelunes =
                    'Se ha dado de alta el Turno del Lunes' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        if ($martesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (MARTES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => idfacultativo,
                'diasemana' => 'MARTES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Martes
                $turnosfacultativo->setDiasemana('MARTES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciomartes
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinmartes
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorafin($horaconvertida);
                $turnosfacultativo->setIdfacultativo($facultativo);
                dump($turnosfacultativo);
                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensajemartes =
                    'Se ha modificado el Turno del Martes' .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Martes
                $nuevoturno->setDiasemana('MARTES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciomartes
                );
                dump($horaconvertida);
                $nuevoturno->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinmartes
                );
                dump($horaconvertida);
                $nuevoturno->setHorafin($horaconvertida);
                $nuevoturno->setIdfacultativo($facultativo);
                dump($nuevoturno);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensajemartes =
                    'Se ha dado de alta el Turno del Martes' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        if ($miercolesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (MIERCOLES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => idfacultativo,
                'diasemana' => 'MIERCOLES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Miercoles
                $turnosfacultativo->setDiasemana('MIERCOLES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciomiercoles
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinmiercoles
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorafin($horaconvertida);
                $turnosfacultativo->setIdfacultativo($facultativo);
                dump($turnosfacultativo);
                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensajemiercoles =
                    'Se ha modificado el Turno del Miercoles' .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Miercoles
                $nuevoturno->setDiasemana('MIERCOLES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciomiercoles
                );
                dump($horaconvertida);
                $nuevoturno->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinmiercoles
                );
                dump($horaconvertida);
                $nuevoturno->setHorafin($horaconvertida);
                $nuevoturno->setIdfacultativo($facultativo);
                dump($nuevoturno);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensajemiercoles =
                    'Se ha dado de alta el Turno del Miercoles' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        if ($juevesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (JUEVES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => idfacultativo,
                'diasemana' => 'JUEVES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Jueves
                $turnosfacultativo->setDiasemana('JUEVES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciojueves
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinjueves
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorafin($horaconvertida);
                $turnosfacultativo->setIdfacultativo($facultativo);
                dump($turnosfacultativo);
                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensajejueves =
                    'Se ha modificado el Turno del Jueves' .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Jueves
                $nuevoturno->setDiasemana('JUEVES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainiciojueves
                );
                dump($horaconvertida);
                $nuevoturno->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinjueves
                );
                dump($horaconvertida);
                $nuevoturno->setHorafin($horaconvertida);
                $nuevoturno->setIdfacultativo($facultativo);
                dump($nuevoturno);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensajejueves =
                    'Se ha dado de alta el Turno del Jueves' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        if ($viernesseleccionado === 'true') {
            // Accedemos para ver si existen Turnos para el día para el Facultativo (JUEVES)
            $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
                'idfacultativo' => idfacultativo,
                'diasemana' => 'JUEVES',
            ]);
            // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
            if ($turnosfacultativo) {
                // Actualizo campos para actualizar el Viernes
                $turnosfacultativo->setDiasemana('VIERNES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainicioviernes
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinviernes
                );
                dump($horaconvertida);
                $turnosfacultativo->setHorafin($horaconvertida);
                $turnosfacultativo->setIdfacultativo($facultativo);
                dump($turnosfacultativo);
                // Modifico registro en la tabla de Turnos
                $em->persist($turnosfacultativo);
                $em->flush();
                $mensajeviernes =
                    'Se ha modificado el Turno del Viernes' .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
            } else {
                // Declaro variable de clase entidad Turno
                $nuevoturno = new Turnos();
                // Añado valores a cada uno de los campos para el registro del Viernes
                $nuevoturno->setDiasemana('VIERNES');
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horainicioviernes
                );
                dump($horaconvertida);
                $nuevoturno->setHorainicio($horaconvertida);
                $horaconvertida = \DateTime::createFromFormat(
                    'H:i:s',
                    $horafinviernes
                );
                dump($horaconvertida);
                $nuevoturno->setHorafin($horaconvertida);
                $nuevoturno->setIdfacultativo($facultativo);
                dump($nuevoturno);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevoturno);
                $em->flush();
                $mensajeviernes =
                    'Se ha dado de alta el Turno del Viernes' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        // Devuelvo control a Pagina Inicio de Administrador mandando mensajes por cada dia
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' => 'Se han modificado los turnos del Facultativo',
        ]);
    }
}
