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
    //*** PENDIENTE  */ 
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
    #[Route('/busquedacitapacientetelefono', name: 'busquedaCitaPacienteTelefono')]
    public function busquedaCitaPacienteTelefono(
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