<?php
namespace App\Controller;

use App\Entity\Vacaciones;
use App\Form\VacacionesType;
use App\Repository\VacacionesRepository;
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

#[Route('/vacaciones')]
class VacacionesController extends AbstractController
{
    //******************************************************************************
    // Alta/Modificacion de Vacaciones de Facultativo por parte del Administrativo *
    //******************************************************************************
    #[Route('/buscarvacacionesfacultativo', name: 'buscarVacacionesFacultativo', methods: ['GET', 'POST'])]
    public function buscarVacacionesFacultativo(
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
        return $this->render('vacaciones/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $facultativos,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Apellido
    #[Route('/buscarvacacionesfacultativoApellido', name: 'buscarVacacionesFacultativoApellido', methods: ['GET', 'POST'])]
    public function buscarVacacionesFacultativoApellido(
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

        return $this->render('vacaciones/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $facultativos,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Telefono
    #[Route('/buscarvacacionesfacultativoTelefono', name: 'buscarVacacionesFacultativoTelefono', methods: ['GET', 'POST'])]
    public function buscarVacacionesFacultativoTelefono(
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
        return $this->render('vacaciones/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $facultativos,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Formulario para mostrar Vacaciones si existen Datos de Facultativos y Formulario para añadir/modificar
    #[Route('/mostrarvacacionesadmin', name: 'mostrarVacacionesAdmin', methods: ['GET', 'POST'])]
    public function mostrarVacacionesAdmin(
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
        $vacacionesfacultativo = $em
            ->getRepository(Vacaciones::class)
            ->findByIdfacultativo($idfacultativo);
        dump($vacacionesfacultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Envio a la vista de Datos Turnos Facultativo y Datos de Facultativo
        return $this->render('vacaciones/altaVacacionesAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosVacaciones' => $vacacionesfacultativo,
        ]);
    }

    // Recogemos Datos Formulario para modificar Vacaciones si ya existen o Darlos de Alta si no existen
    #[Route('/altavacacionesadmin', name: 'altaVacacionesAdmin', methods: ['GET', 'POST'])]
    public function altaVacacionesAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recogemos boton pulsado
        $boton = $request->request->get('operacion');

        // Recogemos datos de formulario con Post del día de vacaciones
        $diavacaciones = $request->request->get('txtfecha');
        dump($diavacaciones);
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $diavacaciones);
        dump($diaconvertido);

        // Si se pulso el boton de Insertar
        if ($boton == 'insertar') {
            // Accedemos para ver si existe ese dia de vacaciones para el Facultativo
            $existevacaciones = $em
                ->getRepository(Vacaciones::class)
                ->findOneBy([
                    'idfacultativo' => $idfacultativo,
                    'fecha' => $diaconvertido,
                ]);
            // Si no existe el dia de vacaciones se da de Alta
            if (!$existevacaciones) {
                // Declaro variable de clase entidad Vacaciones
                $nuevodiavacaciones = new Vacaciones();
                // Añado valores a cada uno de los campos para el registro del Lunes
                $nuevodiavacaciones->setDianotrabajado('true');
                $nuevodiavacaciones->setFecha($diaconvertido);

                // Accedemos al objeto Facultativo para guardarlo con el registro de vacaciones
                $facultativo = $em
                    ->getRepository(Facultativos::class)
                    ->findOneByIdfacultativo($idfacultativo);
                dump($facultativo);
                // Añado el Facultativo
                $nuevodiavacaciones->setIdfacultativo($facultativo);

                dump($nuevodiavacaciones);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevodiavacaciones);
                $em->flush();

                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'Se ha dado de alta el día ' .
                    $diaformateado .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si ya existe el dia de vacaciones se manda mensaje
            } else {
                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'Ya existe el dia ' .
                    $diaformateado .
                    ' de vacaciones que se quiere dar de alta' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        // Si se pulso el boton de Borrar
        if ($boton == 'eliminar') {
            // Accedemos para ver si existe ese dia de vacaciones para el Facultativo
            $existevacaciones = $em
                ->getRepository(Vacaciones::class)
                ->findOneBy([
                    'idfacultativo' => $idfacultativo,
                    'fecha' => $diaconvertido,
                ]);
            // Si existe el dia de vacaciones se da de Baja
            if ($existevacaciones) {
                // Realizamos Baja del dia de vacaciones
                $em->remove($existevacaciones);
                $em->flush();

                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'Se ha dado de baja el día ' .
                    $diaformateado .
                    ' para el facultativo ' .
                    $idfacultativo;
                // Si no existe el dia de vacaciones se manda mensaje
            } else {
                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'No existe el dia ' .
                    $diaformateado .
                    ' de vacaciones que se quiere dar de baja' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }

    //**********************************************************************************
    // Alta/Modificacion de Vacaciones de Facultativo por parte del propio Facultativo *
    //**********************************************************************************
    #[Route('/mostrarvacaciones', name: 'mostrarVacacionesFacultativo', methods: ['GET', 'POST'])]
    public function mostrarVacacionesFacultativo(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero las variables de sesion de usuario y facultativo
        $idusuario = $request->getSession()->get('idusuario');
        $idfacultativo = $request->getSession()->get('idfacultativo');
        dump($idusuario);
        dump($idfacultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);
        dump($facultativo);

        // Recupero datos de turnos de facultativo para enviar los Values a Formulario
        $vacacionesfacultativo = $em
            ->getRepository(Vacaciones::class)
            ->findByIdfacultativo($idfacultativo);
        dump($vacacionesfacultativo);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        // Envio a la vista de Datos Turnos Facultativo y Datos de Facultativo
        return $this->render('vacaciones/altaVacacionesFacultativo.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosVacaciones' => $vacacionesfacultativo,
        ]);
    }

    // Recogemos Datos Formulario para modificar Vacaciones si ya existen o Darlos de Alta si no existen
    #[Route('/altavacacionesfacultativo', name: 'altaFacultativoVacaciones', methods: ['GET', 'POST'])]
    public function altaFacultativoVacaciones(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');
        dump($idfacultativo);

        // Recogemos boton pulsado
        $boton = $request->request->get('operacion');

        // Recogemos datos de formulario con Post del día de vacaciones
        $diavacaciones = $request->request->get('txtfecha');
        dump($diavacaciones);
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $diavacaciones);
        dump($diaconvertido);

        // Si se pulso el boton de Insertar
        if ($boton == 'insertar') {
            // Accedemos para ver si existe ese dia de vacaciones para el Facultativo
            $existevacaciones = $em
                ->getRepository(Vacaciones::class)
                ->findOneBy([
                    'idfacultativo' => $idfacultativo,
                    'fecha' => $diaconvertido,
                ]);
            // Si no existe el dia de vacaciones se da de Alta
            if (!$existevacaciones) {
                // Declaro variable de clase entidad Vacaciones
                $nuevodiavacaciones = new Vacaciones();
                // Añado valores a cada uno de los campos para el registro del Lunes
                $nuevodiavacaciones->setDianotrabajado('true');
                $nuevodiavacaciones->setFecha($diaconvertido);

                // Accedemos al objeto Facultativo para guardarlo con el registro de vacaciones
                $facultativo = $em
                    ->getRepository(Facultativos::class)
                    ->findOneByIdfacultativo($idfacultativo);
                dump($facultativo);
                // Añado el Facultativo
                $nuevodiavacaciones->setIdfacultativo($facultativo);

                dump($nuevodiavacaciones);
                // Inserto registro en la tabla de Turnos
                $em->persist($nuevodiavacaciones);
                $em->flush();

                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'Se ha dado de alta el día ' .
                    $diaformateado .
                    ' de vacaciones ';
                // Si ya existe el dia de vacaciones se manda mensaje
            } else {
                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'Ya existe el dia ' .
                    $diaformateado .
                    ' de vacaciones que se quiere dar de alta';
            }
        }

        // Si se pulso el boton de Borrar
        if ($boton == 'eliminar') {
            // Accedemos para ver si existe ese dia de vacaciones para el Facultativo
            $existevacaciones = $em
                ->getRepository(Vacaciones::class)
                ->findOneBy([
                    'idfacultativo' => $idfacultativo,
                    'fecha' => $diaconvertido,
                ]);
            // Si existe el dia de vacaciones se da de Baja
            if ($existevacaciones) {
                // Realizamos Baja del dia de vacaciones
                $em->remove($existevacaciones);
                $em->flush();

                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'Se ha dado de baja el día ' .
                    $diaformateado .
                    ' de vacaciones ';
                // Si no existe el dia de vacaciones se manda mensaje
            } else {
                $mensajedia = strtotime($diavacaciones);
                $diaformateado = date('d-m-Y', $mensajedia);
                $mensaje =
                    'No existe el dia ' .
                    $diaformateado .
                    ' de vacaciones que se quiere dar de baja';
            }
        }

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardFacultativo.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }
}
