<?php
namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\RegistrationFormType;
use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use App\Entity\Facultativos;
use App\Form\FacultativosType;
use App\Repository\FacultativosRepository;
use App\Entity\Provincias;
use App\Entity\Especialidades;
use Doctrine\ORM\EntityManagerInterface;

use App\Security\FisioterapiaAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

// Use necesario para usar las funciones de paginacion
use Knp\Component\Pager\PaginatorInterface;

#[Route('/administrativo')]
class AdministrativoController extends AbstractController
{
    //********************************************************************
    // Alta de Paciente, Usuario y Paciente por parte del Administrativo *
    //********************************************************************
    #[Route('/nuevopaciente', name: 'altaPacienteAdmin', methods: ['GET', 'POST'])]
    public function altaPacienteAdmin(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        FisioterapiaAuthenticator $authenticator,
        EntityManagerInterface $em
    ): Response {
        // Creo entidad Usuario
        $usuario = new Usuarios();
        $formularioUsuario = $this->createForm(
            RegistrationFormType::class,
            $usuario
        );
        $formularioUsuario->handleRequest($request);

        // Si el formulario de Registro es valido se da de Alta usuario
        if (
            $formularioUsuario->isSubmitted() &&
            $formularioUsuario->isValid()
        ) {
            // Se codifica Password
            $usuario->setPassword(
                $userPasswordHasher->hashPassword(
                    $usuario,
                    $formularioUsuario->get('plainPassword')->getData()
                )
            );

            // Se modifican variables de Entidad. Rol usuario Paciente y cuenta verificada
            $usuario->setRoles(['ROLE_PACIENTE']);
            $usuario->setIsVerified(true);

            $em->persist($usuario);
            $em->flush();

            // No se puede acceder con getUser porque recupera el usuario conectado.
            // $idusuario = $this->getUser()->getIdusuario();
            $idusuario = $usuario->getIdusuario();
            // No se guardan variables de Sesion porque estamos dando de alta un usuario diferente al conectado
            // Se redirige a Formulario de Datos Paciente para dar Alta paciente pero en ruta Admin
            // para no realizar ciertas acciones y mandar el objeto usuario
            return $this->redirectToRoute('insertarPacienteAdmin', [
                'idusuario' => $idusuario,
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $formularioUsuario->createView(),
        ]);
    }

    #[Route('/altapaciente', name: 'insertarPacienteAdmin', methods: ['GET', 'POST'])]
    public function insertarPacienteAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Usuario que me llega con Get (por seguridad deberia ser con Post no con Get)
        $idusuario = $request->query->get('idusuario');

        $paciente = new Pacientes();
        $formularioPaciente = $this->createForm(
            PacientesType::class,
            $paciente
        );

        $formularioPaciente->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioPaciente->isSubmitted() &&
            $formularioPaciente->isValid()
        ) {
            // Declaramos el Array datosformulario que contendra los diferentes campos recuperados
            $datosformulario = $formularioPaciente->getData();

            // Se accede al objeto usuario para guardarlo en Tabla Pacientes
            // $usuario = $em->getRepository(Usuarios::class)->find($idusuario);
            // No se puede acceder con getRepository porque recupera el usuario conectado. Hacemos select
            $usuario = $em
                ->getRepository(Usuarios::class)
                ->findOneByIdusuario($idusuario);

            // Guardo el usuario antes de guardar Paciente con el objeto usuario
            $paciente->setIdusuario($usuario);

            $em->persist($paciente);
            $em->flush();

            // Recupero el Id del Paciente guardado
            $idpaciente = $paciente->getIdpaciente();

            $mensaje =
                'Se ha dado de alta el Paciente con codigo ' . $idpaciente;

            // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Envio a la vista de Datos Paciente mandando el formulario
        return $this->render('pacientes/altaPaciente.html.twig', [
            'pacienteForm' => $formularioPaciente->createView(),
        ]);
    }

    //**************************************************************************
    // Alta de Facultativo, Usuario y Facultativo por parte del Administrativo *
    //**************************************************************************
    #[Route('/nuevofacultativo', name: 'altaFacultativoAdmin', methods: ['GET', 'POST'])]
    public function altaFacultativoAdmin(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        FisioterapiaAuthenticator $authenticator,
        EntityManagerInterface $em
    ): Response {
        // Creo entidad Usuario
        $usuario = new Usuarios();
        $formularioUsuario = $this->createForm(
            RegistrationFormType::class,
            $usuario
        );

        $formularioUsuario->handleRequest($request);

        // Si el formulario de Registro es valido se da de Alta usuario
        if (
            $formularioUsuario->isSubmitted() &&
            $formularioUsuario->isValid()
        ) {
            // Se codifica Password
            $usuario->setPassword(
                $userPasswordHasher->hashPassword(
                    $usuario,
                    $formularioUsuario->get('plainPassword')->getData()
                )
            );

            // Se modifican variables de Entidad. Rol usuario Paciente y cuenta verificada
            $usuario->setRoles(['ROLE_FACULTATIVO']);
            $usuario->setIsVerified(true);

            $em->persist($usuario);
            $em->flush();

            // No se puede acceder con getUser porque recupera el usuario conectado.
            // $idusuario = $this->getUser()->getIdusuario();
            $idusuario = $usuario->getIdusuario();

            // No se guardan variables de Sesion porque estamos dando de alta un usuario diferente al conectado
            // Se redirige a Formulario de Datos Facultativo para dar Alta facultativo
            return $this->redirectToRoute('insertarFacultativoAdmin', [
                'idusuario' => $idusuario,
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $formularioUsuario->createView(),
        ]);
    }

    #[Route('/altafacultativo', name: 'insertarFacultativoAdmin', methods: ['GET', 'POST'])]
    public function insertarFacultativoAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Usuario que me llega con Get (por seguridad deberia ser con Post)
        $idusuario = $request->query->get('idusuario');

        $facultativo = new Facultativos();
        $formularioFacultativo = $this->createForm(
            FacultativosType::class,
            $facultativo
        );

        $formularioFacultativo->handleRequest($request);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioFacultativo->isSubmitted() &&
            $formularioFacultativo->isValid()
        ) {
            // No se puede acceder con getRepository porque recupera el usuario conectado. Hacemos select
            $usuario = $em
                ->getRepository(Usuarios::class)
                ->findOneByIdusuario($idusuario);
            dump($usuario);

            // Guardo el usuario antes de guardar Facultativo con el objeto usuario
            $facultativo->setIdusuario($usuario);

            $em->persist($facultativo);
            $em->flush();

            // Recupero el Id del Facultativo guardado
            $idfacultativo = $facultativo->getIdfacultativo();

            $mensaje =
                'Se ha dado de alta el Facultativo con codigo ' .
                $idfacultativo;

            // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Envio a la vista de Datos Facultativo mandando el formulario
        return $this->render('facultativos/altaFacultativo.html.twig', [
            'facultativoForm' => $formularioFacultativo->createView(),
        ]);
    }

    //***********************************************************************
    // Alta de Usario Administrativo por parte del Administrativo conectado *
    //***********************************************************************
    #[Route('/nuevoadministrativo', name: 'altaAdministrativo', methods: ['GET', 'POST'])]
    public function altaAdministrativo(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        FisioterapiaAuthenticator $authenticator,
        EntityManagerInterface $em
    ): Response {
        // Creo entidad Usuario
        $user = new Usuarios();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // Si el formulario de Registro es valido se da de Alta usuario
        if ($form->isSubmitted() && $form->isValid()) {
            // Se codifica Password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // Se modifican variables de Entidad. Rol usuario Paciente y cuenta verificada
            $user->setRoles(['ROLE_ADMINISTRATIVO']);
            $user->setIsVerified(true);

            $em->persist($user);
            $em->flush();

            // No se puede acceder con getUser porque recupera el usuario conectado.
            // $idusuario = $this->getUser()->getIdusuario();
            $idusuario = $user->getIdusuario();

            // No se guardan variables de Sesion porque estamos dando de alta un usuario diferente al conectado
            // Se redirige a Formulario Dashboard Administrativo mandando mensaje
            $mensaje =
                'Se ha dado de alta el Administrativo con codigo usuario ' .
                $idusuario;

            // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    //**********************************************************
    // Modificar Perfil de Paciente a traves de Administrativo *
    //**********************************************************
    #[Route('/buscarperfilpaciente', name: 'buscarPerfilPacienteAdmin', methods: ['GET', 'POST'])]
    public function buscarPerfilPaciente(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recupero datos de Paccientes para enviar los Values a Formulario con Paginacion
        $query = $em->getRepository(Pacientes::class)->findAll();
        $datosPacientesPaginados = $paginator->paginate(
            $query, // Consulta que quiero paginar,
            $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
            5 // Número de elementos por página
        );

        // Se envia a pagina enviando los datos de los pacientes
        return $this->render('administrativo/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Buscar Perfil Paciente por Apellido
    #[Route('/buscarperfilpacienteApellido', name: 'buscarPerfilPacienteApellidoAdmin', methods: ['GET', 'POST'])]
    public function buscarPerfilPacienteApellido(
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
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT p FROM App\Entity\Pacientes p WHERE p.apellido1 like :parametro'
            );
            // Concateno la variable a buscar y el % del Like
            $query->setParameter('parametro', $busquedaapellido . '%');

            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Si no se relleno se recuperan todos los Pacientes con Paginacion
            $query = $em->getRepository(Pacientes::class)->findAll();

            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        return $this->render('administrativo/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Buscar Perfil Paciente por Telefono
    #[Route('/buscarperfilpacienteTelefono', name: 'buscarPerfilPacienteTelefonoAdmin', methods: ['GET', 'POST'])]
    public function buscarPerfilPacienteTelefono(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        //$busquedatelefono = $request->request->get('txtTelefono');
        $busquedatelefono = $request->query->get('txtTelefono');

        // Si se ha rellenado busqueda telefono
        if ($busquedatelefono) {
            // Si no se relleno se recuperan todos los Pacientes con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->createQuery(
                'SELECT p FROM App\Entity\Pacientes p WHERE p.telefono = :dato'
            );
            // Asigno valor del parametro dato
            $query->setParameter('dato', $busquedatelefono);

            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        } else {
            // Si no se relleno se recuperan todos los Pacientes con Paginacion
            // $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository(Pacientes::class)->findAll();

            $datosPacientesPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('administrativo/busquedaPaciente.html.twig', [
            'datosPacientes' => $datosPacientesPaginados,
        ]);
    }

    // Formulario para mostrar Perfil y Datos de Pacientes y Formulario para modificar
    #[Route('/mostrarpaciente', name: 'mostrarPacienteAdmin', methods: ['GET', 'POST'])]
    public function mostrarPacienteAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Paciente que me llega
        $idpaciente = $request->query->get('idpaciente');

        // Recupero datos de paciente para enviar los Values a Formulario
        $pacientemodificar = $em
            ->getRepository(Pacientes::class)
            ->findOneByIdpaciente($idpaciente);

        // Recupero el Id del Usuario de ese Paciente
        $idusuario = $pacientemodificar->getIdusuario();

        // Recupero datos de usuario para enviar los Values a Formulario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);

        // Recupero todas las Provincias para combo Seleccion (Recupera Array)
        $provincias = $em->getRepository(Provincias::class)->findAll();

        // Envio a la vista de Datos Perfil Paciente
        return $this->render(
            'administrativo/modificarPerfilPaciente.html.twig',
            [
                'datosUsuario' => $usuariomodificar,
                'datosPaciente' => $pacientemodificar,
                'datosProvincias' => $provincias,
            ]
        );
    }

    // Recogemos Datos Formulario para modificar Perfil y Datos de Pacientes
    #[Route('/modificarpaciente', name: 'modificarPacienteAdmin', methods: ['GET', 'POST'])]
    public function modificarPacienteAdmin(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idusuario = $request->query->get('idusuario');
        $idpaciente = $request->query->get('idpaciente');

        /// Recogemos datos de formulario con Post
        $email = $request->request->get('txtEmail');
        dump($email);
        $nombre = $request->request->get('txtNombre');
        dump($nombre);
        $apellido1 = $request->request->get('txtApellido1');
        dump($apellido1);
        $apellido2 = $request->request->get('txtApellido2');
        dump($apellido2);
        $telefono = $request->request->get('txtTelefono');
        dump($telefono);
        $direccion = $request->request->get('txtDireccion');
        dump($direccion);
        $codigopostal = $request->request->get('txtCodigopostal');
        dump($codigopostal);
        $poblacion = $request->request->get('txtLocalidad');
        dump($poblacion);
        $idprovincia = $request->request->get('comboProvincia');
        dump($idprovincia);

        // Recupero datos de objeto Provincia antes de guardar Paciente
        $provincia = $em
            ->getRepository(Provincias::class)
            ->findOneByIdprovincia($idprovincia);

        // Recupero datos de objeto Usuario con el idusuario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);

        // Modifico el Email del usuario con el recibido en formulario
        $usuariomodificar->setEmail($email);

        // Recupero el registro a modificar
        $pacientemodificar = $em
            ->getRepository(Pacientes::class)
            ->find($idpaciente);

        // Modificamos los valores de Paciente con los datos del Formulario, el ID no se puede modificar es clave
        $pacientemodificar->setNombre($nombre);
        $pacientemodificar->setApellido1($apellido1);
        $pacientemodificar->setApellido2($apellido2);
        $pacientemodificar->setTelefono($telefono);
        $pacientemodificar->setCodigoPostal($codigopostal);
        $pacientemodificar->setPoblacion($poblacion);
        $pacientemodificar->setProvincia($provincia);
        // Guardo el usuario antes de guardar Paciente con el objeto usuario
        $pacientemodificar->setIdusuario($usuariomodificar);

        // Modificamos el Usuario
        $em->persist($usuariomodificar);
        $em->flush();

        // Modificamos el Paciente
        $em->persist($pacientemodificar);
        $em->flush();

        // Construimos mensaje de modificacion correcta
        $mensaje = 'Se ha modificado los Datos del Paciente ' . $idpaciente;

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }

    //*************************************************************
    // Modificar Perfil de Facultativo a traves de Administrativo *
    //*************************************************************
    #[Route('/buscarperfilfacultativo', name: 'buscarPerfilFacultativo', methods: ['GET', 'POST'])]
    public function buscarPerfilFacultativo(
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

        // Se envia a pagina enviando los datos de los facultativos
        return $this->render('administrativo/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
        ]);
    }

    // Buscar Perfil Facultativo por Apellido
    #[Route('/buscarperfilfacultativoApellido', name: 'buscarPerfilFacultativoApellido', methods: ['GET', 'POST'])]
    public function buscarPerfilFacultativoApellido(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em,
        PaginatorInterface $paginator
    ) {
        // Recogemos datos de formulario con Get dado que es una busqueda
        $busquedaapellido = $request->query->get('txtApellido');

        // Si se ha rellenado la busqueda por Apellido
        if ($busquedaapellido) {
            // Si no se relleno se recuperan todos los Facultativos con Paginacion
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
            dump($query);
            $datosFacultativosPaginados = $paginator->paginate(
                $query, // Consulta que quiero paginar,
                $request->query->getInt('page', 1), // Definir el parámetro de la página recogida por GET
                5 // Número de elementos por página
            );
        }

        return $this->render('administrativo/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
        ]);
    }

    // Buscar Perfil Facultativo por Telefono
    #[Route('/buscarperfilfacultativoTelefono', name: 'buscarPerfilFacultativoTelefono', methods: ['GET', 'POST'])]
    public function buscarPerfilFacultativoTelefono(
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
            // Select de Pacientes con Where mandado por parametro
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

        // Enviamos a la pagina con los datos de Pacientes recuperados
        return $this->render('administrativo/busquedaFacultativo.html.twig', [
            'datosFacultativos' => $datosFacultativosPaginados,
        ]);
    }

    // Formulario para mostrar Perfil y Datos de Facultativos y Formulario para modificar
    #[Route('/mostrarfacultativo', name: 'mostrarFacultativoAdmin', methods: ['GET', 'POST'])]
    public function mostrarFacultativoAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero el Facultativo que me llega
        $idfacultativo = $request->query->get('idfacultativo');

        // Recupero datos de facultativo para enviar los Values a Formulario
        $facultativomodificar = $em
            ->getRepository(Facultativos::class)
            ->findOneByIdfacultativo($idfacultativo);

        // Recupero el Id del Usuario de ese Facultativo
        $idusuario = $facultativomodificar->getIdusuario();

        // Recupero datos de usuario para enviar los Values a Formulario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);

        // Recupero todas las Especialidades para combo Seleccion (Recupera Array)
        $especialidades = $em->getRepository(Especialidades::class)->findAll();

        // Envio a la vista de Datos Perfil Paciente
        return $this->render(
            'administrativo/modificarPerfilFacultativo.html.twig',
            [
                'datosUsuario' => $usuariomodificar,
                'datosFacultativo' => $facultativomodificar,
                'datosEspecialidades' => $especialidades,
            ]
        );
    }

    // Recogemos Datos Formulario para modificar Perfil y Datos de Pacientes
    #[Route('/modificarfacultativo', name: 'modificarFacultativoAdmin', methods: ['GET', 'POST'])]
    public function modificarFacultativoAdmin(
        Request $request,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recogemos los parametros enviados con get (query->get) no por post (request->get)
        $idusuario = $request->query->get('idusuario');
        $idfacultativo = $request->query->get('idfacultativo');

        /// Recogemos datos de formulario con Post
        $email = $request->request->get('txtEmail');
        dump($email);
        $nombre = $request->request->get('txtNombre');
        dump($nombre);
        $apellido1 = $request->request->get('txtApellido1');
        dump($apellido1);
        $apellido2 = $request->request->get('txtApellido2');
        dump($apellido2);
        $telefono = $request->request->get('txtTelefono');
        dump($telefono);
        $idespecialidad = $request->request->get('comboEspecialidad');
        dump($idespecialidad);

        // Recupero datos de objeto Especialidad antes de guardar Facultativo
        $especialidad = $em
            ->getRepository(Especialidades::class)
            ->findOneByIdespecialidad($idespecialidad);

        // Recupero datos de objeto Usuario con el idusuario
        $usuariomodificar = $em
            ->getRepository(Usuarios::class)
            ->findOneByIdusuario($idusuario);

        // Modifico el Email del usuario con el recibido en formulario
        $usuariomodificar->setEmail($email);

        // Recupero el registro a modificar
        $facultativomodificar = $em
            ->getRepository(Facultativos::class)
            ->find($idfacultativo);

        // Modificamos los valores de Facultativo con los datos del Formulario, el ID no se puede modificar es clave
        $facultativomodificar->setNombre($nombre);
        $facultativomodificar->setApellido1($apellido1);
        $facultativomodificar->setApellido2($apellido2);
        $facultativomodificar->setTelefono($telefono);
        $facultativomodificar->setEspecialidad($especialidad);
        // Guardo el usuario antes de guardar Paciente con el objeto usuario
        $facultativomodificar->setIdusuario($usuariomodificar);

        // Modificamos el Usuario
        $em->persist($usuariomodificar);
        $em->flush();

        // Modificamos el Paciente
        $em->persist($facultativomodificar);
        $em->flush();

        // Construimos mensaje de modificacion correcta
        $mensaje =
            'Se ha modificado los Datos del Facultativo ' . $idfacultativo;

        // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
        return $this->render('dashboard/dashboardAdministrativo.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }
}
