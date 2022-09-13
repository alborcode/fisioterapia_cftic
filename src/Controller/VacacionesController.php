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

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

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
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Si no se relleno se recuperan todos los Facultativos con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Facultativos::class)->findAll();

        $datosFacultativosPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            10 // Número de elementos por página
        );

        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Se envia a pagina enviando los datos de los facultativos
        return $this->render('vacaciones/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Apellido
    #[Route('/buscarvacacionesfacultativoApellido', name: 'buscarVacacionesFacultativoApellido', methods: ['GET', 'POST'])]
    public function buscarVacacionesFacultativoApellido(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        // $busquedaapellido = $request->request->get('txtApellido');
        $busquedaapellido = $request->query->get('txtApellido');

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.apellido1 like :parametro'
            );
            // Concateno la variable a buscar y el % del Like
            $query->setParameter('parametro', $busquedaapellido . '%');

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                10 // Número de elementos por página
            );
        } else {
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Facultativos::class)->findAll();

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                10 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();
        dump($especialidades);

        return $this->render('vacaciones/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Buscar Turnos Facultativo por Telefono
    #[Route('/buscarvacacionesfacultativoTelefono', name: 'buscarVacacionesFacultativoTelefono', methods: ['GET', 'POST'])]
    public function buscarVacacionesFacultativoTelefono(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        //$busquedatelefono = $request->request->get('txtTelefono');
        $busquedatelefono = $request->query->get('txtTelefono');

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT f FROM App\Entity\Facultativos f WHERE f.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                10 // Número de elementos por página
            );
        } else {
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Facultativos::class)->findAll();

            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                10 // Número de elementos por página
            );
        }

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('vacaciones/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
            'datosEspecialidades' => $especialidades,
        ]);
    }

    // Formulario para mostrar Vacaciones si existen Datos de Facultativos y Formulario para añadir/modificar
    #[Route('/mostrarvacacionesadmin', name: 'mostrarVacacionesAdmin', methods: ['GET', 'POST'])]
    public function mostrarVacacionesAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero el Facultativo que me llega
        // $idfacultativo = $request->request->get('idfacultativo');
        $idfacultativo = $request->query->get('idfacultativo');

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero vacaciones de facultativo ordenadas por fecha para enviar los Values con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository(Vacaciones::class)
            ->findBy(['idfacultativo' => $idfacultativo], ['fecha' => 'ASC']);

        $datosVacacionesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            10 // Número de elementos por página
        );

        // Recuperar Fecha minima y maxima
        $anio = date('Y');
        $fechaini = $anio . '-01' . '-01';
        $fechafin = $anio . '-12' . '-31';
        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');
        dump($fechadia);

        // Recupero de API los Festivos de la Comunidad de Madrid (fecha_festivo dara las fechas en formato Y-m-d)
        $datos = file_get_contents(
            'https://datos.comunidad.madrid/catalogo/dataset/2f422c9b-47df-407f-902d-4a2f44dd435e/resource/453162e0-bd61-4f52-8699-7ed5f33168f6/download/festivos_regionales.json'
        );
        $datosjson = json_decode($datos, true);
        // En el Array guardo los datos Json de data con los registros
        $festivosregionales = $datosjson['data'];
        // Recupero un Array solo de las Fechas de Festivos
        $festivosarray = array_column($festivosregionales, 'fecha_festivo');

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Vacaciones, Datos Facultativo y Especialidades
        return $this->render('vacaciones/altaVacacionesAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosVacaciones' => $datosVacacionesPaginados,
            'fechaini' => $fechaini,
            'fechafin' => $fechafin,
            'fechadia' => $fechadia,
            'festivosregionales' => $festivosarray,
        ]);
    }

    // Recogemos Datos Formulario para modificar Vacaciones si ya existen o Darlos de Alta si no existen
    #[Route('/altavacacionesadmin', name: 'altaVacacionesAdmin', methods: ['GET', 'POST'])]
    public function altaVacacionesAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');

        // Recogemos boton pulsado
        $boton = $request->request->get('operacion');

        // Recogemos datos de formulario con Post del día de vacaciones
        $diavacaciones = $request->request->get('txFecha');
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $diavacaciones);

        $mensaje = null;
        $mensajewarning = null;

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
                // Añado el Facultativo
                $nuevodiavacaciones->setIdfacultativo($facultativo);

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
                $mensajewarning =
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
                $mensajewarning =
                    'No existe el dia ' .
                    $diaformateado .
                    ' de vacaciones que se quiere dar de baja' .
                    ' para el facultativo ' .
                    $idfacultativo;
            }
        }

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero vacaciones de facultativo ordenadas por fecha para enviar los Values con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository(Vacaciones::class)
            ->findBy(['idfacultativo' => $idfacultativo], ['fecha' => 'ASC']);

        $datosVacacionesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 10), // Definir el parámetro de la página recogida por GET
            3 // Número de elementos por página
        );

        // Recuperar Año actual
        $anio = date('Y');
        $fechaini = $anio . '-01' . '-01';
        $fechafin = $anio . '-12' . '-31';
        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');

        // Recupero de API los Festivos de la Comunidad de Madrid (fecha_festivo dara las fechas en formato Y-m-d)
        $datos = file_get_contents(
            'https://datos.comunidad.madrid/catalogo/dataset/2f422c9b-47df-407f-902d-4a2f44dd435e/resource/453162e0-bd61-4f52-8699-7ed5f33168f6/download/festivos_regionales.json'
        );
        $datosjson = json_decode($datos, true);
        // En el Array guardo los datos Json de data con los registros
        $festivosregionales = $datosjson['data'];
        // Recupero un Array solo de las Fechas de Festivos
        $festivosarray = array_column($festivosregionales, 'fecha_festivo');

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Vacaciones, Datos Facultativo y Especialidades
        return $this->render('vacaciones/altaVacacionesAdmin.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosVacaciones' => $datosVacacionesPaginados,
            'fechaini' => $fechaini,
            'fechafin' => $fechafin,
            'fechadia' => $fechadia,
            'festivosregionales' => $festivosarray,
            'mensaje' => $mensaje,
            'mensajewarning' => $mensajewarning,
        ]);
    }

    //**********************************************************************************
    // Alta/Modificacion de Vacaciones de Facultativo por parte del propio Facultativo *
    //**********************************************************************************
    #[Route('/mostrarvacaciones', name: 'mostrarVacacionesFacultativo', methods: ['GET', 'POST'])]
    public function mostrarVacacionesFacultativo(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero las variables de sesion de usuario y facultativo
        $idusuario = $request->getSession()->get('idusuario');
        $idfacultativo = $request->getSession()->get('idfacultativo');

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero vacaciones de facultativo ordenadas por fecha para enviar los Values con Paginacion
        // $em = $this->getDoctrine()->getManager();
        $query = $em
            ->getRepository(Vacaciones::class)
            ->findBy(['idfacultativo' => $idfacultativo], ['fecha' => 'ASC']);

        $datosVacacionesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            10 // Número de elementos por página
        );

        // Recuperar Año actual
        $anio = date('Y');
        $fechaini = $anio . '-01' . '-01';
        $fechafin = $anio . '-12' . '-31';
        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');

        // Recupero de API los Festivos de la Comunidad de Madrid (fecha_festivo dara las fechas en formato Y-m-d)
        $datos = file_get_contents(
            'https://datos.comunidad.madrid/catalogo/dataset/2f422c9b-47df-407f-902d-4a2f44dd435e/resource/453162e0-bd61-4f52-8699-7ed5f33168f6/download/festivos_regionales.json'
        );
        $datosjson = json_decode($datos, true);
        // En el Array guardo los datos Json de data con los registros
        $festivosregionales = $datosjson['data'];
        // Recupero un Array solo de las Fechas de Festivos
        $festivosarray = array_column($festivosregionales, 'fecha_festivo');

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Datos Turnos Facultativo y Datos de Facultativo
        return $this->render('vacaciones/altaVacacionesFacultativo.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosVacaciones' => $datosVacacionesPaginados,
            'fechaini' => $fechaini,
            'fechafin' => $fechafin,
            'fechadia' => $fechadia,
            'festivosregionales' => $festivosarray,
        ]);
    }

    // Recogemos Datos Formulario para modificar Vacaciones si ya existen o Darlos de Alta si no existen
    #[Route('/altavacacionesfacultativo', name: 'altaFacultativoVacaciones', methods: ['GET', 'POST'])]
    public function altaFacultativoVacaciones(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idfacultativo = $request->query->get('idfacultativo');

        // Recogemos boton pulsado
        $boton = $request->request->get('operacion');

        // Recogemos datos de formulario con Post del día de vacaciones
        $diavacaciones = $request->request->get('txFecha');
        $diaconvertido = \DateTime::createFromFormat('Y-m-d', $diavacaciones);

        $mensaje = null;
        $mensajewarning = null;

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

                // Añado el Facultativo
                $nuevodiavacaciones->setIdfacultativo($facultativo);

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
                $mensajewarning =
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
                $mensajewarning =
                    'No existe el dia ' .
                    $diaformateado .
                    ' de vacaciones que se quiere dar de baja';
            }
        }

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativo = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero vacaciones de facultativo ordenadas por fecha para enviar los Values con Paginacion
        $query = $em
            ->getRepository(Vacaciones::class)
            ->findBy(['idfacultativo' => $idfacultativo], ['fecha' => 'ASC']);

        $datosVacacionesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            10 // Número de elementos por página
        );

        // Recuperar Año actual
        $anio = date('Y');
        $fechaini = $anio . '-01' . '-01';
        $fechafin = $anio . '-12' . '-31';
        // Recupero Fecha del Dia
        $fechadia = date('Y-m-d');

        // Recupero de API los Festivos de la Comunidad de Madrid (fecha_festivo dara las fechas en formato Y-m-d)
        $datos = file_get_contents(
            'https://datos.comunidad.madrid/catalogo/dataset/2f422c9b-47df-407f-902d-4a2f44dd435e/resource/453162e0-bd61-4f52-8699-7ed5f33168f6/download/festivos_regionales.json'
        );
        $datosjson = json_decode($datos, true);
        // En el Array guardo los datos Json de data con los registros
        $festivosregionales = $datosjson['data'];
        // Recupero un Array solo de las Fechas de Festivos
        $festivosarray = array_column($festivosregionales, 'fecha_festivo');

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Datos Turnos Facultativo y Datos de Facultativo
        return $this->render('vacaciones/altaVacacionesFacultativo.html.twig', [
            'datosFacultativo' => $facultativo,
            'datosEspecialidades' => $especialidades,
            'datosVacaciones' => $datosVacacionesPaginados,
            'fechaini' => $fechaini,
            'fechafin' => $fechafin,
            'fechadia' => $fechadia,
            'festivosregionales' => $festivosarray,
            'mensaje' => $mensaje,
            'mensajewarning' => $mensajewarning,
        ]);
    }
}
