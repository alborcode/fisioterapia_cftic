<?php
namespace App\Controller;

use App\Entity\Vacaciones;
use App\Form\VacacionesType;
use App\Repository\VacacionesRepository;
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

#[Route('/vacaciones')]
class VacacionesController extends AbstractController
{
    //*****************************************************************
    // Alta de Vacaciones de Facultativo por parte del Administrativo *
    //*****************************************************************
    // Mostrara la vista con todos los facultativos para seleccionar el que se quiere gestionar
    #[Route('/altadia', name: 'seleccionVacaciones', methods: ['GET', 'POST'])]
    public function seleccionVacaciones(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em
    ): Response {
        // Recupero todos los Facultativos
        $facultativos = $em->getRepository(Facultativos::class)->findAll();

        return $this->render('vacaciones/altaVacaciones.html.twig', [
            'datosFacultativos' => $facultativos,
        ]);
    }

    //*************************************************************************
    // Alta de Vacaciones por Administrativo una vez seleccionado Facultativo *
    //*************************************************************************
    // Mostrar datos facultativo cuadro seleccion turno (vacaciones o baja) y boton de Alta
    #[Route('/altavacaciones', name: 'altaVacaciones', methods: ['GET', 'POST'])]
    public function altaVacaciones(
        Request $request,
        FacultativosRepository $facultativosRepository,
        VacacionesRepository $vacacionesRepository,
        EntityManagerInterface $em
    ) {
        // Creo entidad Vacacciones
        $vacaciones = new Vacaciones();
        $formularioVacaciones = $this->createForm(
            VacacionesType::class,
            $vacaciones
        );
        $formularioVacaciones->handleRequest($request);

        // Se recoge con get el facultativo que hemos mandado
        $idfacultativo = $request->query->get('idfacultativo');
        dump($facultativo);

        // Se valida si el formulario es correcto para guardar los datos
        if (
            $formularioVacaciones->isSubmitted() &&
            $formularioVacaciones->isValid()
        ) {
            // Recogemos los campos del Formulario en Array para tratarlos
            //$data = $form->getData();

            // Se recupera objeto facultativo con el id mandado
            $facultativo = $em
                ->getRepository(Facultativos::class)
                ->findOneByIdfacultativo($idfacultativo);
            dump($facultativo);
            // Guardo el facultativo antes de guardar Turno con el objeto facultativo
            $vacaciones->setIdfacultativo($facultativo);

            // Guardamos en Base de Datos
            $em->persist($vacaciones);
            $em->flush();

            // Recupero el Dia guardado para mostrarlo en mensaje
            $fecha = $vacaciones->getFecha();
            dump($fecha);
            // Recupero si es dia de vacaciones o de baja para  mensaje
            $dianotrabajado = $vacaciones->isDianotrabajado();
            $diadebaja = $vacaciones->isDiadebaja();
            if ($dianotrabajado) {
                $textodia = ' día de vacaciones';
            } else {
                $textodia = ' día de baja';
            }

            $mensaje =
                'Se ha dado de alta el' .
                $textodia .
                ' para el facultativo ' .
                $idfacultativo;

            // Devuelvo control a Pagina Inicio de Administrador mandando mensaje
            return $this->render(
                'dashboard/dashboardAdministrativo.html.twig',
                [
                    'mensaje' => $mensaje,
                ]
            );
        }

        // Recupero todos las Vacaciones del Facultativo
        $vacacionesdealta = $em
            ->getRepository(Vacaciones::class)
            ->findBy(['idfacultativo' => idfacultativo], ['fecha' => 'DESC']);

        // Envio a la vista de Datos Administrativo mandando el formulario y los turnos de alta
        return $this->render('turnos/altaTurno.html.twig', [
            'vacacionesForm' => $formularioVacaciones->createView(),
            'datosVacaciones' => $vacacionesdealta,
            'datosFacultativo' => $facultativo,
        ]);
    }
}
