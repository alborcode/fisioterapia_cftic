<?php
namespace App\Controller;

use App\Entity\Turnos;
use App\Form\TurnosType;
use App\Repository\TurnosRepository;
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

#[Route('/turnos')]
class TurnosController extends AbstractController
{
    //************************************************************
    // Alta de Turno de Facultativo por parte del Administrativo *
    //************************************************************
    // Mostrara la vista con todos los facultativos para seleccionar el que se quiere gestionar
    #[Route('/nuevoturno', name: 'seleccionTurno', methods: ['GET', 'POST'])]
    public function seleccionTurno(
        Request $request,
        FacultativosRepository $facultativosRepository,
        TurnosRepository $turnosRepository,
        EntityManagerInterface $em
    ): Response {
        // Recupero todos los Facultativos
        $facultativos = $em->getRepository(Facultativos::class)->findAll();

        return $this->render('turnos/altaTurno.html.twig', [
            'datosFacultativos' => $facultativos,
        ]);
    }

    //*********************************************************************
    // Alta de Turnos por Administrativo una vez seleccionado Facultativo *
    //*********************************************************************
    // Mostrar datos facultativo cuadro seleccion turno (mañana o tarde) y boton de Alta
    #[Route('/altaturno', name: 'altaTurno', methods: ['GET', 'POST'])]
    public function altaTurno(
        Request $request,
        FacultativosRepository $facultativosRepository,
        TurnosRepository $turnosRepository,
        EntityManagerInterface $em
    ) {
        // Creo entidad Turnos
        $turnos = new Turnos();
        $formularioTurnos = $this->createForm(TurnosType::class, $turnos);
        $formularioTurnos->handleRequest($request);

        // Se recoge con get el facultativo que hemos mandado
        $idfacultativo = $request->query->get('idfacultativo');
        dump($facultativo);

        // Se valida si el formulario es correcto para guardar los datos
        if ($formularioTurnos->isSubmitted() && $formularioTurnos->isValid()) {
            // Recogemos los campos del Formulario en Array para tratarlos
            //$data = $form->getData();
            // Controlamos según se haya elegido Mañana o Tarde las horas de inicio y fin
            $turno = $request->request->get('name');
            if ($turno == 'MAÑANA') {
                $horainicio = '09:00';
                $horafin = '14:00';
                $turnos->setHorainicio($horainicio);
                $turnos->setHorafin($horafin);
            } else {
                $horainicio = '15:00';
                $horafin = '20:00';
                $turnos->setHorainicio($horainicio);
                $turnos->setHorafin($horafin);
            }

            // Se recupera objeto facultativo con el idfacultativo
            $facultativo = $em
                ->getRepository(Facultativos::class)
                ->findOneByIdfacultativo($idfacultativo);
            dump($facultativo);
            // Guardo el facultativo antes de guardar Turno con el objeto facultativo
            $turnos->setIdfacultativo($facultativo);

            // Guardamos en Base de Datos
            $em->persist($turnos);
            $em->flush();

            // Recupero el Dia de la semana guardado para mostrarlo en mensaje
            $diasemana = $turnos->getDiasemana();
            dump($diasemana);
            // Recupero el Turno guardado para mostrarlo en mensaje
            $turno = $turnos->getTurno();
            dump($turno);

            $mensaje =
                'Se ha dado de alta el' .
                $diasemana .
                ' del turno de ' .
                $turno .
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

        // Recupero todos los Turnos del Facultativo
        $turnosdealta = $em->getRepository(Turnos::class)->findAll();

        // Envio a la vista de Datos Administrativo mandando el formulario y los turnos de alta
        return $this->render('turnos/altaTurno.html.twig', [
            'turnosForm' => $formularioTurnos->createView(),
            'datosTurnos' => $turnosdealta,
            'datosFacultativo' => $facultativo,
        ]);
    }
}
