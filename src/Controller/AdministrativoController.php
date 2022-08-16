<?php
namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\RegistrationFormType;
use App\Repository\UsuariosRepository;
use App\Entity\Pacientes;
use App\Form\PacientesType;
use App\Repository\PacientesRepository;
use App\Entity\Facultativos;
use App\Form\FacultativosType;
use App\Repository\FacultativosRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Security\EmailVerifier;
use App\Security\FisioterapiaAuthenticator;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

#[Route('/admin')]
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
            $user->setRoles(['ROLE_PACIENTE']);
            $user->setIsVerified(true);

            $em->persist($user);
            $em->flush();

            // No se puede acceder con getUser porque recupera el usuario conectado.
            // $idusuario = $this->getUser()->getIdusuario();
            $idusuario = $user->getIdusuario();
            dump('idusuario:' . $idusuario);
            // No se guardan variables de Sesion porque estamos dando de alta un usuario diferente al conectado
            // Se redirige a Formulario de Datos Paciente para dar Alta paciente pero en ruta Admin
            // para no realizar ciertas acciones y mandar el objeto usuario
            return $this->redirectToRoute('insertarPacienteAdmin', [
                'idusuario' => $idusuario,
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
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
        //$idusuario = $request->request->get('idusuario');
        // Imprimo el objeto usuario
        dump($idusuario);

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
            dump($paciente);
            dump($formularioPaciente);

            // Se accede al objeto usuario para guardarlo en Tabla Pacientes
            // $usuario = $em->getRepository(Usuarios::class)->find($idusuario);
            // No se puede acceder con getRepository porque recupera el usuario conectado. Hacemos select
            $usuario = $em
                ->getRepository(Usuarios::class)
                ->findOneByIdusuario($idusuario);
            dump($usuario);
            // Guardo el usuario antes de guardar Paciente con el objeto usuario
            $paciente->setIdusuario($usuario);
            dump($paciente);

            $em->persist($paciente);
            $em->flush();
            //$pacientesRepository->add($paciente, true);
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
            $user->setRoles(['ROLE_FACULTATIVO']);
            $user->setIsVerified(true);

            $em->persist($user);
            $em->flush();

            // No se puede acceder con getUser porque recupera el usuario conectado.
            // $idusuario = $this->getUser()->getIdusuario();
            $idusuario = $user->getIdusuario();
            dump('idusuario:' . $idusuario);
            // No se guardan variables de Sesion porque estamos dando de alta un usuario diferente al conectado
            // Se redirige a Formulario de Datos Facultativo para dar Alta facultativo
            return $this->redirectToRoute('insertarFacultativoAdmin', [
                'idusuario' => $idusuario,
            ]);
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
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
        //$idusuario = $request->request->get('idusuario');
        // Imprimo el objeto usuario
        dump($idusuario);

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
            dump($facultativo);
            dump($formularioFacultativo);
            // No se puede acceder con getRepository porque recupera el usuario conectado. Hacemos select
            $usuario = $em
                ->getRepository(Usuarios::class)
                ->findOneByIdusuario($idusuario);
            dump($usuario);
            // Guardo el usuario antes de guardar Facultativo con el objeto usuario
            $facultativo->setIdusuario($usuario);
            dump($facultativo);

            $em->persist($facultativo);
            $em->flush();
            //$pacientesRepository->add($paciente, true);
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
            dump('idusuario:' . $idusuario);
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

    //********************************************************************
    // Busqueda de Paciente para Alta de Cita a traves de Administrativo *
    //********************************************************************
    // Muestra Combo para Seleccionar Administrativo cargado con datos BBDD
    // Mostrar cuadro busqueda para filtrar y con todos los Pacientes para Seleccionar
    #[Route('/buscarpacientefacultativo', name: 'buscarPacienteFacultativo', methods: ['GET', 'POST'])]
    public function buscarPacienteFacultativo(
        Request $request,
        PacientesRepository $pacientesRepository,
        FacultativosRepository $facultativosRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos los Pacientes
        $pacientes = $em->getRepository(Pacientes::class)->findAll();
        // Recupero todos los Facultativos
        $facultativos = $em->getRepository(Facultativos::class)->findAll();

        return $this->render('citas/buscarCita.html.twig', [
            'datosPacientes' => $pacientes,
            'datosFacultativos' => $facultativos,
        ]);
    }

    // Mostrar Datos de Pacientes segun busqueda introducida por Apellido con Like
    #[Route('/busquedapacienteapellido', name: 'busquedaPacienteApellido', methods: ['GET', 'POST'])]
    public function busquedaPacienteApellido(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos los Facultativos
        $facultativos = $em->getRepository(Facultativos::class)->findAll();

        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post
        $datobusqueda = $request->request->get('txtApellido');
        dump($datobusqueda);

        $query = $em->createQuery(
            'SELECT e FROM App\Entity\Pacientes p WHERE p.apellido1 like :parametro'
        );
        // Concateno la variable a buscar y el % del Like
        $query->setParameter('parametro', $datobusqueda . '%');
        dump($query);

        $pacientes = $query->getResult();
        dump($datos);

        return $this->render('citas/mostrarPaciente.html.twig', [
            'datosPacientes' => $pacientes,
            'datosFacultativos' => $facultativos,
        ]);
    }

    // Mostrar Datos de Pacientes segun busqueda unica por telefono
    #[Route('/busquedapacientetelefono', name: 'busquedaPacienteTelefono')]
    public function busquedaPacienteTelefono(
        Request $request,
        PacientesRepository $pacientesRepository,
        EntityManagerInterface $em
    ) {
        // Recupero todos los Facultativos
        $facultativos = $em->getRepository(Facultativos::class)->findAll();

        // Se recoge con get el parametro que hemos mandado en la caja de texto request al ser post, query si es get
        $datoget = $request->request->get('txtTelefono');
        // Select de Pacientes con Where mandado por parametro (por inyeccion SQL no ponemos $datoget)
        $query = $em->createQuery(
            'SELECT p FROM App\Entity\Pacientes p WHERE p.telefono = :dato'
        );
        // Asigno valor del parametro dato
        $query->setParameter('dato', $datoget);
        // Al hacer el getresult ejecuta la Query y obtiene los resultados
        $pacientes = $query->getResult();

        return $this->render('citas/mostrarPaciente.html.twig', [
            'datosPacientes' => $pacientes,
            'datosFacultativos' => $facultativos,
        ]);
    }

    //**********************************************************************************
    // Busqueda de Cita por Administrativo una vez seleccionado Facultativo y Paciente *
    //**********************************************************************************
    // Mostrar cuadro busqueda para filtrar y con todos los Pacientes para Seleccionar
    #[Route('/seleccioncita', name: 'seleccionarCita', methods: ['GET', 'POST'])]
    public function seleccionarCita(
        Request $request,
        PacientesRepository $pacientesRepository,
        FacultativosRepository $facultativosRepository,
        CitasRepository $citasRepository,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el paciente que hemos mandado
        $paciente = $request->query->get('paciente');
        // Se recoge con get el facultativo que hemos mandado
        $paciente = $request->query->get('facultativo');
        // Recupero las citas libres de ese Facultativo.

        return $this->render('citas/buscarCita.html.twig', [
            'paciente' => $paciente,
            'facultativo' => $facultativo,
        ]);
    }
}
