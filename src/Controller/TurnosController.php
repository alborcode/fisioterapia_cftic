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
            ->findOneByIdfacultativo($idfacultativo);
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
    #[Route('/modificarfacultativo', name: 'modificarFacultativoAdmin', methods: ['GET', 'POST'])]
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
        $turnolunes = $request->request->get('comboTurnolunes');
        $horainiciolunes = $request->request->get('txtHorainiciolunes');
        $horafinlunes = $request->request->get('txtHorafinlunes');
        dump($turnolunes . ' De ' . $horainiciolunes . ' a ' . $horafinlunes);

        $turnomartes = $request->request->get('comboTurnomartes');
        $horainiciomartes = $request->request->get('txtHorainiciomartes');
        $horafinmartes = $request->request->get('txtHorafinmartes');
        dump(
            $turnomartes . ' De ' . $horainiciomartes . ' a ' . $horafinmartes
        );

        $turnomiercoles = $request->request->get('comboTurnomiercoles');
        $horainiciomiercoles = $request->request->get('txtHorainiciomiercoles');
        $horafinmiercoles = $request->request->get('txtHorafinmiercoles');
        dump(
            $turnomiercoles .
                ' De ' .
                $horainiciomiercoles .
                ' a ' .
                $horafinmiercoles
        );

        $turnojueves = $request->request->get('comboTurnojueves');
        $horainiciojueves = $request->request->get('txtHorainiciojueves');
        $horafinjueves = $request->request->get('txtHorafinjueves');
        dump(
            $turnojueves . ' De ' . $horainiciojueves . ' a ' . $horafinjueves
        );

        $turnoviernes = $request->request->get('comboTurnoviernes');
        $horainicioviernes = $request->request->get('txtHorainicioviernes');
        $horafinviernes = $request->request->get('txtHorafinviernes');
        dump(
            $turnoviernes .
                ' De ' .
                $horainicioviernes .
                ' a ' .
                $horafinviernes
        );

        // Accedemos al objeto Facultativo para guardarlo por cada uno de los registros de turno
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Accedemos para ver si existen Turnos para el día para el Facultativo (LUNES)
        $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
            'idfacultativo' => $idfacultativo,
            'diasemana' => 'LUNES',
        ]);

        // $userinfo->setBirthday(new \DateTime($norm_date));
        // $userinfo->setBirthday(new \DateTime('now'));
        // $userinfo->setBirthday(new \DateTime('2013-01-15'));
        // $userinfo->setBirthday(new \DateTime('+2 days'), new \DateTimeZone('UCT'));
        // $lastmonth = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        // $today = date("H:i:s");                         // 17:16:18
        // $today = date("Y-m-d H:i:s");                   // 2001-03-10 17:16:18 (the MySQL DATETIME format)

        // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
        if ($turnosfacultativo) {
            // Actualizo campos para actualizar el Lunes
            $turnosfacultativo->setDiasemana('LUNES');
            dump('despues de setDiasemana');
            $turnosfacultativo->setTurno($turnolunes);
            dump('despues de setTurno');
            $horainicio = TimeInmutable::createFromFormat(
                'H:i:s',
                $horainiciolunes
            );
            dump($horainicio);
            $turnosfacultativo->setHorainicio($horainicio);
            dump('despues de setHorainicio');
            $turnosfacultativo->setHorafin($horafinlunes);
            dump('despues de setHorafin');
            $turnosfacultativo->setIdfacultativo($facultativo);
            dump('despues de setIdfacultativo');
            dump($turnosfacultativo);
            // Modifico registro en la tabla de Turnos
            $em->persist($turnosfacultativo);
            $em->flush();
            $mensajelunes =
                'Se ha modificado el Lunes' .
                ' del turno de ' .
                $turnolunes .
                ' para el facultativo ' .
                $idfacultativo;
            // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
        } else {
            // Declaro variable de clase entidad Turno
            $nuevoturno = new Turnos();
            // Añado valores a cada uno de los campos para el registro del Lunes
            $nuevoturno->setDiasemana('LUNES');
            $nuevoturno->setTurno($turnolunes);
            $horainicio = TimeInmutable::createFromFormat(
                'H:i:s',
                $horainiciolunes
            );
            $nuevoturno->setHorainicio($horainicio);
            $nuevoturno->setHorafin($horafinlunes);
            $nuevoturno->setIdfacultativo($facultativo);
            dump($nuevoturno);
            // Inserto registro en la tabla de Turnos
            $em->persist($nuevoturno);
            $em->flush();
            $mensajelunes =
                'Se ha dado de alta el Lunes' .
                ' del turno de ' .
                $turnolunes .
                ' para el facultativo ' .
                $idfacultativo;
        }

        // Accedemos para ver si existen Turnos para el día para el Facultativo (MARTES)
        $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
            'idfacultativo' => idfacultativo,
            'diasemana' => 'MARTES',
        ]);
        // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
        if ($turnosfacultativo) {
            // Actualizo campos para actualizar el Martes
            $turnosfacultativo->setDiasemana('MARTES');
            $turnosfacultativo->setTurno($turnomartes);
            $turnosfacultativo->setHorainicio($horainiciomartes);
            $turnosfacultativo->setHorafin($horafinmartes);
            $turnosfacultativo->setIdfacultativo($facultativo);
            dump($turnosfacultativo);
            // Modifico registro en la tabla de Turnos
            $em->persist($turnosfacultativo);
            $em->flush();
            $mensajemartes =
                'Se ha modificado el Martes' .
                ' del turno de ' .
                $turnomartes .
                ' para el facultativo ' .
                $idfacultativo;
            // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
        } else {
            // Declaro variable de clase entidad Turno
            $nuevoturno = new Turnos();
            // Añado valores a cada uno de los campos para el registro del Martes
            $nuevoturno->setDiasemana('MARTES');
            $nuevoturno->setTurno($turnomartes);
            $nuevoturno->setHorainicio($horainiciomartes);
            $nuevoturno->setHorafin($horafinmartes);
            $nuevoturno->setIdfacultativo($facultativo);
            dump($nuevoturno);
            // Inserto registro en la tabla de Turnos
            $em->persist($nuevoturno);
            $em->flush();
            $mensajemartes =
                'Se ha dado de alta el Martes' .
                ' del turno de ' .
                $turnomartes .
                ' para el facultativo ' .
                $idfacultativo;
        }

        // Accedemos para ver si existen Turnos para el día para el Facultativo (MIERCOLES)
        $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
            'idfacultativo' => idfacultativo,
            'diasemana' => 'MIERCOLES',
        ]);
        // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
        if ($turnosfacultativo) {
            // Actualizo campos para actualizar el Miercoles
            $turnosfacultativo->setDiasemana('MIERCOLES');
            $turnosfacultativo->setTurno($turnomiercoles);
            $turnosfacultativo->setHorainicio($horainiciomiercoles);
            $turnosfacultativo->setHorafin($horafinmiercoles);
            $turnosfacultativo->setIdfacultativo($facultativo);
            dump($turnosfacultativo);
            // Modifico registro en la tabla de Turnos
            $em->persist($turnosfacultativo);
            $em->flush();
            $mensajemiercoles =
                'Se ha modificado el Miercoles' .
                ' del turno de ' .
                $turnomiercoles .
                ' para el facultativo ' .
                $idfacultativo;
            // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
        } else {
            // Declaro variable de clase entidad Turno
            $nuevoturno = new Turnos();
            // Añado valores a cada uno de los campos para el registro del Miercoles
            $nuevoturno->setDiasemana('MIERCOLES');
            $nuevoturno->setTurno($turnomiercoles);
            $nuevoturno->setHorainicio($horainiciomiercoles);
            $nuevoturno->setHorafin($horafinmiercoles);
            $nuevoturno->setIdfacultativo($facultativo);
            dump($nuevoturno);
            // Inserto registro en la tabla de Turnos
            $em->persist($nuevoturno);
            $em->flush();
            $mensajemiercoles =
                'Se ha dado de alta el Miercoles' .
                ' del turno de ' .
                $turnomiercoles .
                ' para el facultativo ' .
                $idfacultativo;
        }

        // Accedemos para ver si existen Turnos para el día para el Facultativo (JUEVES)
        $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
            'idfacultativo' => idfacultativo,
            'diasemana' => 'JUEVES',
        ]);
        // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
        if ($turnosfacultativo) {
            // Actualizo campos para actualizar el Jueves
            $turnosfacultativo->setDiasemana('JUEVES');
            $turnosfacultativo->setTurno($turnojueves);
            $turnosfacultativo->setHorainicio($horainiciojueves);
            $turnosfacultativo->setHorafin($horafinjueves);
            $turnosfacultativo->setIdfacultativo($facultativo);
            dump($turnosfacultativo);
            // Modifico registro en la tabla de Turnos
            $em->persist($turnosfacultativo);
            $em->flush();
            $mensajejueves =
                'Se ha modificado el Jueves' .
                ' del turno de ' .
                $turnojueves .
                ' para el facultativo ' .
                $idfacultativo;
            // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
        } else {
            // Declaro variable de clase entidad Turno
            $nuevoturno = new Turnos();
            // Añado valores a cada uno de los campos para el registro del Jueves
            $nuevoturno->setDiasemana('JUEVES');
            $nuevoturno->setTurno($turnojueves);
            $nuevoturno->setHorainicio($horainiciojueves);
            $nuevoturno->setHorafin($horafinjueves);
            $nuevoturno->setIdfacultativo($facultativo);
            dump($nuevoturno);
            // Inserto registro en la tabla de Turnos
            $em->persist($nuevoturno);
            $em->flush();
            $mensajejueves =
                'Se ha dado de alta el Jueves' .
                ' del turno de ' .
                $turnojueves .
                ' para el facultativo ' .
                $idfacultativo;
        }

        // Accedemos para ver si existen Turnos para el día para el Facultativo (JUEVES)
        $turnosfacultativo = $em->getRepository(Turnos::class)->findOneBy([
            'idfacultativo' => idfacultativo,
            'diasemana' => 'JUEVES',
        ]);
        // Si existen los Turnos del facultativo se modificaran (Se recupera un registro por dia)
        if ($turnosfacultativo) {
            // Actualizo campos para actualizar el Viernes
            $turnosfacultativo->setDiasemana('VIERNES');
            $turnosfacultativo->setTurno($turnoviernes);
            $turnosfacultativo->setHorainicio($horainicioviernes);
            $turnosfacultativo->setHorafin($horafinviernes);
            $turnosfacultativo->setIdfacultativo($facultativo);
            dump($turnosfacultativo);
            // Modifico registro en la tabla de Turnos
            $em->persist($turnosfacultativo);
            $em->flush();
            $mensajeviernes =
                'Se ha modificado el Viernes' .
                ' del turno de ' .
                $turnoviernes .
                ' para el facultativo ' .
                $idfacultativo;
            // Si no existen los Turnos del Facultativo se dan de alta (uno por dia recuperados en Formulario)
        } else {
            // Declaro variable de clase entidad Turno
            $nuevoturno = new Turnos();
            // Añado valores a cada uno de los campos para el registro del Viernes
            $nuevoturno->setDiasemana('VIERNES');
            $nuevoturno->setTurno($turnoviernes);
            $nuevoturno->setHorainicio($horainicioviernes);
            $nuevoturno->setHorafin($horafinviernes);
            $nuevoturno->setIdfacultativo($facultativo);
            dump($nuevoturno);
            // Inserto registro en la tabla de Turnos
            $em->persist($nuevoturno);
            $em->flush();
            $mensajeviernes =
                'Se ha dado de alta el Viernes' .
                ' del turno de ' .
                $turnoviernes .
                ' para el facultativo ' .
                $idfacultativo;
        }

        // Devuelvo control a Pagina Inicio de Administrador mandando mensajes por cada dia
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje1' => $mensajelunes,
            'mensaje2' => $mensajemartes,
            'mensaje3' => $mensajemiercoles,
            'mensaje4' => $mensajejueves,
            'mensaje5' => $mensajeviernes,
        ]);
    }
}
